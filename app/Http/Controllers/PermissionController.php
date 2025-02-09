<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public $indexof = 1;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $priority = Permission::query();
            return DataTables::of($priority)
                ->addColumn('id', function ($data) {
                    return $this->indexof++;
                })
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('action', function ($data) {
                    $button = '<div class="change-action-item d-none">';
                    $button .= '<a title="Edit"  href="' . route('permission.edit', $data->id) . '"  class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                    $button .= '<a  href="' . route('permission.delete', $data->id) . '"  class="btn btn-danger btn-sm delete" title="Delete"><i class="fa fa-fw fa-trash"></i></a>';
                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.permission.list');
    }

    public function create()
    {
        $permission = null;
        return view('backend.permission.add', compact('permission'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:permissions,name',
        ];

        $this->validate($request, $rules);

        Permission::create([
            'name' => $request->name,
        ]);

        return redirect()->route('permission.index')->with('success', "Permission has been created!");
    }

    public function edit($id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return redirect()->route('permission.index');
        }
        return view(
            'backend.permission.add',
            compact(
                'permission',
            )
        );
    }
    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);
        $rules = [
            'name' => 'required',
        ];
        $this->validate($request, $rules);

        $permission->update([
            'name' => $request->name,
        ]);
        return redirect()->route('permission.index')->with('success', "Department has been updated!");
    }
    public function destroy($id)
    {
        $permission = Permission::find($id);
        $permission->delete();
        return redirect()->back()->with('success', "Permission has been deleted!");
    }
}
