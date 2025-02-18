<?php

namespace App\Http\Controllers;

use App\Http\Helpers\AppHelper;
use App\Models\Status;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view status', ['only' => ['index']]);
        // $this->middleware('permission:create role', ['only' => ['create', 'store']]);
        // $this->middleware('permission:update role', ['only' => ['update', 'edit']]);
        // $this->middleware('permission:delete role', ['only' => ['destroy']]);
    }
    public $indexof = 1;
    public function index(Request $request) 
    {
        $status = Status::query();
        if ($request->ajax()) {
            return DataTables::of($status)
            ->addIndexColumn() // This automatically adds DT_RowIndex
                ->filter(function ($query) use ($request) {
                    if ($search = $request->input('search.value')) {
                        $query->where(function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%{$search}%");
                        });
                    }
                })
                // ->addColumn('action', function ($data) {
                //     $button = '<div class="change-action-item">';
                //     $button.='<a title="Edit"  href="'.route('status.edit',$data->id).'"  class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                //     // $button.='<a  href="'.route('status.destroy',$data->id).'"  class="btn btn-danger btn-sm delete" title="Delete"><i class="fa fa-fw fa-trash"></i></a>';
                //     $button.='</div>';
                //     return $button;
                // })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.status.list');
    }

    public function edit($id)
    {
        $status = Status::findOrFail($id);
        return view('backend.status.edit', compact('status'));
    }

}
