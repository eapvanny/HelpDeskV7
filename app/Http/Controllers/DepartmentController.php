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
    public $indexof = 1;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $departments = Department::query();
            return DataTables::of($departments)
                ->addColumn('id', function ($data) {
                    return $this->indexof++;
                })
                ->addColumn('code', function ($data) {
                    return $data->code;
                })
                ->addColumn('name', function ($data) {
                    return __($data->name);
                })
                ->addColumn('name_in_latin', function ($data) {
                    return __($data->name_in_latin);
                })
                ->addColumn('abbreviation', function ($data) {
                    return __($data->abbreviation);
                })
                ->addColumn('action', function ($data) {
                    return '<div class="change-action-item">
                        <a title="Edit" href="' . route('department.edit', $data->id) . '" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                        <a href="' . route('department.delete', $data->id) . '" class="btn btn-danger btn-sm delete" title="Delete"><i class="fa fa-fw fa-trash"></i></a>
                    </div>';
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
