<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\UserController;
use App\Models\Permission;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('backend.login');
})->middleware('auth');
Route::get('/login', function () {
    return view('backend.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('user.login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forget-password', [AuthController::class, 'forgetPassword'])->name('forget.password');
Route::post('/forget-password', [AuthController::class, 'forgetPasswordPost'])->name('forget.password.post');
Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('reset.password');
Route::post('/reset-password', [AuthController::class, 'resetPasswordPost'])->name('reset.password.post');

Route::group(['middleware' => ['auth', 'isAdmin']], function () {

    Route::resource('dashboard', DashboardController::class);
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/ticket-data', [DashboardController::class, 'getTicketData'])->name('dashboard.ticket-data');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    // Route::post('/profile', [UserController::class, 'profile'])->name('profile');

    //User
    Route::resource('user', UserController::class);
    Route::get('change-password', [UserController::class, 'showChangePasswordForm'])->name('change_password');
    Route::post('change-password', [UserController::class, 'changePassword'])->name('update_password');
    Route::get('/lock', [UserController::class, 'lock'])->name('lockscreen');

    Route::post('/user/disable/{id}', [UserController::class, 'disable'])->name('user.disable');
    Route::post('/user/enable/{id}', [UserController::class, 'enable'])->name('user.enable');
    // Route::get('/user', [UserController::class, 'index'])->name('user.index');
    // Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    // Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    // Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    // Route::put('/user/update/{id}', [UserController::class, 'update'])->name('user.update');
    // Route::delete('/user/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');
    Route::post('/users/{id}/update-profile-photo', [UserController::class, 'updateProfilePhoto'])
        ->name('users.updateProfilePhoto');

    // Route for switching languages
    Route::get('/set-lang/{lang}', [UserController::class, 'setLanguage'])->name('user.set_lang');


    //role
    Route::resource('role', RoleController::class);

    // Route::get('/role', [RoleController::class, 'index'])->name('role.index');
    // Route::get('/role/create', [RoleController::class, 'create'])->name('role.create');
    // Route::post('/role/store', [RoleController::class, 'store'])->name('role.store');
    // Route::get('/role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
    // Route::put('/role/update/{id}', [RoleController::class, 'update'])->name('role.update');
    // Route::delete('/role/delete/{id}', [RoleController::class, 'destroy'])->name('role.delete');

    //permission
    Route::resource('permission', PermissionController::class);

    // Route::get('/permission', [PermissionController::class, 'index'])->name('permission.index');
    // Route::get('/permission/create', [PermissionController::class, 'create'])->name('permission.create');
    // Route::post('/permission/store', [PermissionController::class, 'store'])->name('permission.store');
    // Route::get('/permission/{id}/edit', [PermissionController::class, 'edit'])->name('permission.edit');
    // Route::put('/permission/update/{id}', [PermissionController::class, 'update'])->name('permission.update');
    // Route::delete('/permission/delete/{id}', [PermissionController::class, 'destroy'])->name('permission.delete');

    //Department
    Route::resource('department', DepartmentController::class);
    // Route::get('/department', [DepartmentController::class, 'index'])->name('department.index');
    // Route::get('/department/create', [DepartmentController::class, 'create'])->name('department.create');
    // Route::post('/department/store', [DepartmentController::class, 'store'])->name('department.store');
    // Route::get('/department/{id}/edit', [DepartmentController::class, 'edit'])->name('department.edit');
    // Route::put('/department/update/{id}', [DepartmentController::class, 'update'])->name('department.update');
    // Route::delete('/department/{id}/delete', [DepartmentController::class, 'destroy'])->name('department.delete');

    //Ticket
    Route::get('/ticket/requests', [TicketController::class, 'getRequestTickets'])->name('ticket.requests');
    Route::get('/ticket/accepted', [TicketController::class, 'getAcceptedTickets'])->name('ticket.accepted');
    Route::get('/ticket/rejected', [TicketController::class, 'getRejectedTickets'])->name('ticket.rejected');
    Route::post('/ticket/update-status/{id}', [TicketController::class, 'updateStatus'])->name('ticket.update-status');
    Route::resource('ticket', TicketController::class);
    // Route::get('/ticket', [TicketController::class, 'index'])->name('ticket.index');
    // Route::get('/ticket/create', [TicketController::class, 'create'])->name('ticket.create');
    // Route::get('/ticket/{id}/show', [TicketController::class, 'show'])->name('ticket.show');
    // Route::post('/ticket/store', [TicketController::class, 'store'])->name('ticket.store');
    // Route::get('/ticket/{id}/edit', [TicketController::class, 'edit'])->name('ticket.edit');
    // Route::put('/ticket/update/{id}', [TicketController::class, 'update'])->name('ticket.update');
    // Route::delete('/ticket/{id}/delete', [TicketController::class, 'destroy'])->name('ticket.delete');

    //chat message
    Route::get('/chat/messages/{ticket_id}', [TicketController::class, 'getMessages'])->name('get.messages');
    Route::post('/chat/send', [TicketController::class, 'sendMessage'])->name('send.message');


    //Status
    Route::resource('status', StatusController::class);

    // Route::get('/status', [StatusController::class, 'index'])->name('status.index');
    // Route::get('/status/create', [StatusController::class, 'create'])->name('status.create');
    // Route::get('/status/{id}/edit', [StatusController::class, 'edit'])->name('status.edit');

    //Priority
    Route::resource('priority', PriorityController::class);
    Route::resource('contact', ContactController::class);

    Route::get('support', [DashboardController::class, 'getSupportUser'])->name('get.support');

    // Route::get('/priority', [PriorityController::class, 'index'])->name('priority.index');
    // Route::get('/priority/create', [PriorityController::class, 'create'])->name('priority.create');
    // Route::get('/priority/{id}/edit', [PriorityController::class, 'edit'])->name('priority.edit');


    // Translation Routes
    Route::prefix('translations')->name('translation.')->group(function () {
        // List all translations
        Route::get('/', [TranslationController::class, 'index'])->name('index');

        // Store translation
        Route::post('/store', [TranslationController::class, 'store'])->name('store');

        // Update translation
        Route::put('/{id}', [TranslationController::class, 'update'])->name('update');

        // Delete translation
        Route::delete('/{id}', [TranslationController::class, 'destroy'])->name('destroy');

        // Import translations
        Route::post('/import', [TranslationController::class, 'import'])->name('import');

        //Setting Import
        Route::get('/export', [TranslationController::class, 'export'])->name('export');
    });
});
