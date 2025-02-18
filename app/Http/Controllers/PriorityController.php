<?php

namespace App\Http\Controllers;

use App\Models\Priority;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PriorityController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view priority', ['only' => ['index']]);
        // $this->middleware('permission:create priority', ['only' => ['create', 'store']]);
        // $this->middleware('permission:update priority', ['only' => ['update', 'edit']]);
        // $this->middleware('permission:delete priority', ['only' => ['destroy']]);
    }
    public $indexof = 1;
    public function index(Request $request) 
    {
        $priority = Priority::query();
        if ($request->ajax()) {
            return DataTables::of($priority)
            ->addIndexColumn()
            ->filter(function ($query) use ($request) {
                if ($search = $request->input('search.value')) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
                }
            })
                // ->addColumn('action', function ($data) {
                //     $button = '<div class="change-action-item d-none">';
                //     $button.='<a title="Edit"  href="'.route('priority.edit',$data->id).'"  class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                //     // $button.='<a  href="'.route('ticket.destroy',$data->id).'"  class="btn btn-danger btn-sm delete" title="Delete"><i class="fa fa-fw fa-trash"></i></a>';
                //     $button.='</div>';
                //     return $button;
                // })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.priority.list');
    }
}
