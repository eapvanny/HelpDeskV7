<?php

namespace App\Http\Controllers;

use App\Models\Priority;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PriorityController extends Controller
{
    public $indexof = 1;
    public function index(Request $request) 
    {

        if ($request->ajax()) {
            $priority = Priority::query();
            return DataTables::of($priority)
                ->addColumn('id', function ($data) {
                    return $this->indexof++;
                })
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('action', function ($data) {
                    $button = '<div class="change-action-item">';
                    $button.='<a title="Edit"  href="'.route('priority.edit',$data->id).'"  class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                    // $button.='<a  href="'.route('ticket.delete',$data->id).'"  class="btn btn-danger btn-sm delete" title="Delete"><i class="fa fa-fw fa-trash"></i></a>';
                    $button.='</div>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.priority.list');
    }
}
