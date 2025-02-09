<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public $indexof = 1;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::query();
            return DataTables::of($roles)
                ->addColumn('id', function ($data) {
                    return $this->indexof++;
                })
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('AccessDashboard', function ($data) {
                    if ($data->dashboard_access == 1) {
                        return __('Can Access Dasboard');
                    } else {
                        return __('Can not Access Dashboard');
                    }
                })
                ->addColumn('action', function ($data) {
                    $button = '<div class="change-action-item">';
                    $button .= '<a title="Edit"  href="' . route('role.edit', $data->id) . '"  class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                    $button .= '<a  href="' . route('role.delete', $data->id) . '"  class="btn btn-danger btn-sm delete" title="Delete"><i class="fa fa-fw fa-trash"></i></a>';
                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.role.list');
    }

    public function create()
    {
        $role = null;
        $permissions = Permission::all();
        return view('backend.role.add', compact('role', 'permissions'));
    }


    public function store(Request $request)
    {
        // Validate the incoming request
        $rules = [
            'name' => 'required|min:2|max:25|unique:roles,name', // Ensure the name is unique
            'dashboard_access' => 'nullable|boolean',
            'permissions' => 'array', // Ensure permissions are sent as an array
            'permissions.*' => 'exists:permissions,id'
        ];
        $this->validate($request, $rules);

        $dashboardAccess = $request->has('dashboard_access') ? 1 : 0;

        $role = Role::create([
            'name' => $request->name,
            'dashboard_access' => $dashboardAccess,
        ]);

        if ($request->has('permissions')) {
            foreach ($request->permissions as $permissionId) {
                RolePermission::create([
                    'role_id' => $role->id,
                    'permission_id' => $permissionId,
                ]);
            }
        }

        return redirect()->route('role.index')->with('success', 'Role Created Successfully');
    }

    public function edit($id)
    {
        $role = Role::find($id);
        $permissions = Permission::all();
        $role_permissions = RolePermission::where('role_id', $role->id)->get();

        // Retrieve the permission IDs already assigned to the role
        $rolePermissionIds = $role_permissions->pluck('permission_id')->toArray();

        if (!$role) {
            return redirect()->route('ticket.index');
        }

        return view('backend.role.add', compact('role', 'permissions', 'rolePermissionIds'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return redirect()->route('role.index')->with('error', 'Role not found.');
        }

        $rules = [
            'name' => 'required|min:2|max:25|unique:roles,name,' . $role->id, // Ensure the name is unique except for the current role
            'dashboard_access' => 'nullable|boolean',
            'permissions' => 'array', // Ensure permissions are sent as an array
            'permissions.*' => 'exists:permissions,id'
        ];
        $this->validate($request, $rules);

        $dashboardAccess = $request->has('dashboard_access') ? 1 : 0;

        $role->update([
            'name' => $request->name,
            'dashboard_access' => $dashboardAccess,
        ]);

        // Remove existing role permissions and add the new ones
        $role->permissions()->sync($request->permissions);

        return redirect()->route('role.index')->with('success', "Role has been updated!");
    }


    public function destroy($id)
    {
        $ticket = Role::find($id);
        $ticket->delete();
        return redirect()->back()->with('success', "Ticket has been deleted!");
    }
}
