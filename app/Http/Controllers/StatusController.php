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

        if ($request->ajax()) {
            $status = Status::query();
            return DataTables::of($status)
                ->addColumn('id', function ($data) {
                    return $this->indexof++;
                })
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('action', function ($data) {
                    $button = '<div class="change-action-item d-none">';
                    $button.='<a title="Edit"  href="'.route('status.edit',$data->id).'"  class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                    // $button.='<a  href="'.route('ticket.destroy',$data->id).'"  class="btn btn-danger btn-sm delete" title="Delete"><i class="fa fa-fw fa-trash"></i></a>';
                    $button.='</div>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.status.list');
    }
}
