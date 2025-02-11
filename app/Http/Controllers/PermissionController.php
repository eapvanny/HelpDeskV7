<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:create permission', ['only' => ['index','getTicketData']]);
    //     $this->middleware('permission:view dashboard', ['only' => ['index','getTicketData']]);
    //     $this->middleware('permission:view dashboard', ['only' => ['index','getTicketData']]);
    //     $this->middleware('permission:view dashboard', ['only' => ['index','getTicketData']]);
    //     $this->middleware('permission:view dashboard', ['only' => ['index','getTicketData']]);
    //     $this->middleware('permission:view dashboard', ['only' => ['index','getTicketData']]);
    //     $this->middleware('permission:view dashboard', ['only' => ['index','getTicketData']]);
    // }
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
                    $button = '<div class="change-action-item">';
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions|min:3',
        ]);

        if ($validator->passes()) {
            Permission::create([
                'name' => $request->name,
                'guard_name' => 'web', // Explicitly set 'web' as the guard_name
            ]);
            return redirect()->route('permission.index')->with('success', "Permission has been created!");
        } else {
            return redirect()->route('permission.create')->withInput()->withErrors($validator);
        }
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:permissions,name,' . $id . ',id',
        ]);
        if ($validator->passes()) {
            $permission->name = $request->name;
            $permission->save();
            return redirect()->route('permission.index')->with('success', "Permission has been updated!");
        } else {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }
    public function destroy($id)
    {
        $permission = Permission::find($id);
        $permission->delete();
        return redirect()->back()->with('success', "Permission has been deleted!");
    }
}
