<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Query\Builder;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view department', ['only' => ['index']]);
        $this->middleware('permission:create department', ['only' => ['create', 'store']]);
        $this->middleware('permission:update department', ['only' => ['update', 'edit']]);
        $this->middleware('permission:delete department', ['only' => ['destroy']]);
    }
    public $indexof = 1;
    public function index(Request $request)
    {
        $departments = Department::query();
        if ($request->ajax()) {
            return DataTables::of($departments)
                ->addIndexColumn() // This automatically adds DT_RowIndex
                ->filter(function ($query) use ($request) {
                    if ($search = $request->input('search.value')) {
                        $query->where(function ($q) use ($search) {
                            $q->where('code', 'LIKE', "%{$search}%")
                                ->orWhere('name', 'LIKE', "%{$search}%")
                                ->orWhere('name_in_latin', 'LIKE', "%{$search}%")
                                ->orWhere('abbreviation', 'LIKE', "%{$search}%");
                        });
                    }
                })
                ->addColumn('action', function ($data) {
                    $button = '<div class="change-action-item">';
                    $actions = false;
                    if (auth()->user()->can('update department')) {
                        $button .= '<a title="Edit" href="' . route('department.edit', $data->id) . '" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                        $actions = true;
                    }
                    if (auth()->user()->can('delete department')) {
                        $button .= '<a href="' . route('department.destroy', $data->id) . '" class="btn btn-danger btn-sm delete" title="Delete"><i class="fa fa-fw fa-trash"></i></a>';
                        $actions = true;
                    }
                    if (!$actions) {
                        $button .= '<span style="font-weight:bold; color:red;">No Action</span>';
                    }
                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.department.list');
    }
    public function create()
    {
        $department = null;
        return view(
            'backend.department.add',
            compact(
                'department'
            )
        );
    }
    public function store(Request $request)
    {
        $id = null;
        $rules = [
            'code' => [Rule::unique('departments')->where(function (Builder $query) use ($request, $id) {
                $query->where('deleted_at', null);
            }), 'required', 'max:50'],
            'name' => 'required|max:50',
            'name_in_latin' => 'required|max:50',
            'abbreviation' => 'required|max:50',
        ];
        $this->validate($request, $rules);

        Department::create([
            'code' => $request->code,
            'name' => $request->name,
            'name_in_latin' => $request->name_in_latin,
            'abbreviation' => $request->abbreviation
        ]);

        $department = Department::latest()->first()->id;

        if ($request->has('saveandcontinue')) {
            return redirect()->route('department.create', $department)->with('success', 'Departments added successfully!');
        } else {
            return redirect()->route('department.index')->with('success', "Departments has been created!");
        }
    }
    public function edit($id)
    {
        $department = Department::find($id);
        if (!$department) {
            return redirect()->route('department.index');
        }
        return view(
            'backend.department.add',
            compact(
                'department'
            )
        );
    }
    public function update(Request $request, $id)
    {
        $department = Department::find($id);
        $rules = [
            'code' => [Rule::unique('departments')->where(function (Builder $query) use ($request, $id) {
                $query->where('deleted_at', null);
                $query->where('id', '<>', $id);
            }), 'required', 'max:50'],
            'name' => 'required|max:50',
            'name_in_latin' => 'required|max:50',
            'abbreviation' => 'required|max:50',
        ];
        $this->validate($request, $rules);

        $department->update([
            'code' => $request->code,
            'name' => $request->name,
            'name_in_latin' => $request->name_in_latin,
            'abbreviation' => $request->abbreviation
        ]);
        return redirect()->route('department.index')->with('success', "Department has been updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $department = Department::find($id);
        $department->delete();
        return redirect()->back()->with('success', "Department has been deleted!");
    }
    
    
}
