<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TicketController;
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
});
Route::get('/login', function () {
    return view('backend.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('user.login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/ticket-data', [DashboardController::class, 'getTicketData'])->name('dashboard.ticket-data');

    //Department
    Route::get('/department', [DepartmentController::class, 'index'])->name('department.index');
    Route::get('/department/create', [DepartmentController::class, 'create'])->name('department.create');
    Route::post('/department/store', [DepartmentController::class, 'store'])->name('department.store');
    Route::get('/department/{id}/edit', [DepartmentController::class, 'edit'])->name('department.edit');
    Route::put('/department/update/{id}', [DepartmentController::class, 'update'])->name('department.update');
    Route::delete('/department/{id}/delete', [DepartmentController::class, 'destroy'])->name('department.delete');

    //Ticket
    Route::get('/ticket', [TicketController::class, 'index'])->name('ticket.index');
    Route::get('/ticket/create', [TicketController::class, 'create'])->name('ticket.create');
    Route::post('/ticket/store', [TicketController::class, 'store'])->name('ticket.store');
    Route::get('/ticket/{id}/edit', [TicketController::class, 'edit'])->name('ticket.edit');
    Route::put('/ticket/update/{id}', [TicketController::class, 'update'])->name('ticket.update');
    Route::delete('/ticket/{id}/delete', [TicketController::class, 'destroy'])->name('ticket.delete');

    //Status
    Route::get('/status', [StatusController::class, 'index'])->name('status.index');
    Route::get('/status/create', [StatusController::class, 'create'])->name('status.create');
    Route::get('/status/{id}/edit', [StatusController::class, 'edit'])->name('status.edit');

    //Priority
    Route::get('/priority', [PriorityController::class, 'index'])->name('priority.index');
    Route::get('/priority/create', [PriorityController::class, 'create'])->name('priority.create');
    Route::get('/priority/{id}/edit', [PriorityController::class, 'edit'])->name('priority.edit');

});
