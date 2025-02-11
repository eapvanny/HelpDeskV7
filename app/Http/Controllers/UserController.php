<?php

namespace App\Http\Controllers;

use App\Http\Helpers\AppHelper;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:view user', ['only' => ['index']]);
    //     $this->middleware('permission:create user', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:edit user', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:delete user', ['only' => ['destroy']]);
    // }
    public $indexof = 1;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with('department', 'role')->get();
            return DataTables::of($users)
                ->addColumn('id', function ($data) {
                    return $this->indexof++;
                })
                ->addColumn('photo', function ($data) {
                    return '<img class="img-responsive center" style="height: 35px; width: 35px; object-fit: cover; border-radius: 50%;" 
                    src="' . ($data->photo ? asset('storage/' . $data->photo) : asset('images/avatar.jpg')) . '" >';
                })
                ->addColumn('department', function ($data) {
                    return $data->department ? __($data->department->name) : __('N/A');
                })
                ->addColumn('name', function ($data) {
                    return __($data->name);
                })
                ->addColumn('username', function ($data) {
                    return __($data->username);
                })
                ->addColumn('email', function ($data) {
                    return __($data->email);
                })
                ->addColumn('phone_no', function ($data) {
                    return __($data->phone_no);
                })
                ->addColumn('role', function ($data) {
                    return $data->role ? __($data->role->name) : __('N/A');
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == 1) {
                        return __('Active');
                    } else {
                        return __('Inactive');
                    }
                })
                ->addColumn('action', function ($data) {
                    return '<div class="change-action-item">
                        <a title="Edit" href="' . route('user.edit', $data->id) . '" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                        <a href="' . route('user.delete', $data->id) . '" class="btn btn-danger btn-sm delete" title="Delete"><i class="fa fa-fw fa-trash"></i></a>
                    </div>';
                })
                ->rawColumns(['action', 'photo', 'status'])
                ->make(true);
        }
        return view('backend.user.list');
    }

    public function create()
    {
        $user = null;
        $departments = Department::pluck('name', 'id');
        $roles = Role::pluck('name', 'id');
        return view('backend.user.add', compact('user', 'roles', 'departments'));
    }

    public function store(Request $request)
    {
        $rules = [
            'photo' => 'mimes:jpeg,jpg,png|max:2000|dimensions:min_width=50,min_height=50',
            'name' => 'required|min:2|max:255',
            'department_id' => 'required',
            'email' => 'email|max:255|unique:users,email',
            'username' => 'required|min:5|max:255|unique:users,username',
            'password' => 'required|min:6|max:50',
            'phone_no' => 'nullable|max:15',
            'role_id' => 'required',

        ];

        $this->validate($request, $rules);

        $userData = [
            'name' => $request->name,
            'department_id' => $request->department_id,
            'role_id' => $request->role_id,
            'username' => $request->username,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'status' => $request->status,
            'password' => bcrypt($request->password),
        ];

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '_' . md5($file->getClientOriginalName()) . '.' . $file->extension();
            $filePath = 'uploads/' . $fileName;
            Storage::put($filePath, file_get_contents($file));
            $userData['photo'] = $filePath;
        }

        $user = User::create($userData);

        // Fetch the role name before assigning it
        $role = Role::findOrFail($request->role_id);
        $user->syncRoles($role->name); // Assign role using name

        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('user.index')->with('success', 'User added!');
    }


    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'id');

        $departments = Department::pluck('name', 'id');
        if (!$user) {
            return redirect()->route('user.index');
        }
        return view(
            'backend.user.add',
            compact(
                'user',
                'departments',
                'roles'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id); // Ensure user exists

        // Validation rules
        $rules = [
            'photo' => 'nullable|mimes:jpeg,jpg,png|max:2000|dimensions:min_width=50,min_height=50',
            'name' => 'required|min:2|max:255',
            'department_id' => 'required',
            // 'email' => 'required|email|max:255|unique:users,email,' . $id,
            'username' => 'required|min:5|max:255|unique:users,username,' . $id,
            'password' => 'nullable|min:6|max:50',
            'phone_no' => 'nullable|max:15',
            'role_id' => 'required',
        ];

        $this->validate($request, $rules);

        // Prepare user data for update
        $userData = [
            'name' => $request->name,
            'department_id' => $request->department_id,
            'role_id' => $request->role_id, // You may want to get this dynamically
            'username' => $request->username,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'status' => $request->status,
        ];

        // Handle password update only if provided
        // if ($request->filled('password')) {
        //     $userData['password'] = bcrypt($request->password);
        // }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo) {
                Storage::delete($user->photo);
            }

            $file = $request->file('photo');
            $fileName = time() . '_' . md5($file->getClientOriginalName()) . '.' . $file->extension();
            $filePath = 'uploads/' . $fileName;
            Storage::put($filePath, file_get_contents($file));

            $userData['photo'] = $filePath;
        }

        // Update user record
        $user->update($userData);

        // Assign the user to role only if it doesn’t exist
        UserRole::updateOrCreate(
            ['user_id' => $user->id],
            ['role_id' => $request->role_id]
        );

        return redirect()->route('user.index')->with('success', 'User updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        UserRole::where('user_id', $user->id)->delete();

        $user->delete();

        return redirect()->back()->with('success', "User has been deleted!");
    }


    public function profile(Request $request)
    {
        $storage = Storage::allFiles();

        $user = auth()->user();
        // return redirect()->route('profile')->with('success', 'Profile updated.');

        return view('backend.user.profile', compact('user'));
    }


    public function updateProfilePhoto(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            abort(404);
        }

        $this->validate($request, [
            'photo' => 'mimes:jpeg,jpg,png|max:2000|dimensions:min_width=50,min_height=50',
        ]);

        if ($request->hasFile('photo')) {


            $file = $request->file('photo');
            $fileName = time() . '_' . md5($file->getClientOriginalName()) . '.' . $file->extension();
            $filePath = 'uploads/' . $fileName;
            Storage::put($filePath, file_get_contents($file));
            // $urlPath = Storage::url($filePath);

            $user_photo = $filePath;


            // Delete the old photo if exists
            $oldFile = $user->photo;
            if ($oldFile) {
                Storage::delete($oldFile);
            }

            // Update the student's photo field
            $update = $user->update(['photo' => $user_photo]);
            if ($update) {
                return redirect()->back()->with('success', 'Profile Photo updated!');
            } else {
                return redirect()->back()->with('error', 'Failed to update profile photo in the database.')->withInput();
            }
        }
        return redirect()->back()->with('success', 'Profile Photo updated!');
    }

    public function setLanguage($lang)
    {
        if (in_array($lang, ['en', 'kh'])) {
            // Update the user's language preference (if logged in)
            if (auth()->check()) {
                auth()->user()->update(['user_lang' => $lang]);
            }

            // Store language in session
            session(['user_lang' => $lang]);

            // Set the application's locale
            app()->setLocale($lang);

            // Redirect back
            return redirect()->back();
        }

        return redirect()->route('/dashboard');
    }
}
