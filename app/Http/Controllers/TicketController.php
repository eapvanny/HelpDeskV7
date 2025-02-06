<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Helpers\AppHelper;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
class TicketController extends Controller
{
    public $indexof = 1;
    public function index(Request $request) 
    {
        $tickets = Ticket::whereHas('department')->get();

        if ($request->ajax()) {
            return DataTables::of($tickets)
                ->addColumn('id', function ($data) {
                    return $this->indexof++;
                })
                ->addColumn('subject', function ($data) {
                    return $data->subject;
                })
                ->addColumn('department', function ($data) {
                    return __($data->department->name);
                })
                ->addColumn('status', function ($data) {
                    return AppHelper::STATUS[$data->status_id] ?? 'Unknown';
                })
                ->addColumn('priority', function ($data) {
                    return AppHelper::PRIORITY[$data->priority_id] ?? 'Unknown';
                })
                ->addColumn('action', function ($data) {
                    $button = '<div class="change-action-item">';
                    $button.='<a title="Edit"  href="'.route('ticket.edit',$data->id).'"  class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                    $button.='<a  href="'.route('ticket.delete',$data->id).'"  class="btn btn-danger btn-sm delete" title="Delete"><i class="fa fa-fw fa-trash"></i></a>';
                    $button.='</div>';
                    return $button;
                })
                ->rawColumns(['photo', 'status', 'action'])
                ->make(true);
        }
        return view('backend.ticket.list');
    }
    public function create() 
    {

        $departments = Department::pluck('name','id');
        $ticket = Ticket::query();
        return view('backend.ticket.add', compact('departments','ticket'));

    }
    public function store(Request $request)
    {
        $rules = [
            'department_id' => 'required',
            'subject' => 'required|min:2',
            'description' => 'required',
        ];
        $this->validate($request, $rules);

        Ticket::create([
            'department_id' => $request->department_id,
            'subject' => $request->subject,
            'status_id' => $request->status_id,
            'priority_id' => $request->priority_id,
            'description' => $request->description
        ]);

        return redirect()->route('ticket.index')->with('success', "Tickets has been created!");
    }
    public function edit($id)
    {
        $ticket = Ticket::find($id);
        $departments = Department::pluck('name','id');
        if (!$ticket) {
            return redirect()->route('ticket.index');
        }
        return view(
            'backend.ticket.add',
            compact(
                'ticket',
                'departments'
            )
        );
    }
    public function update(Request $request, $id)
    {
        $ticket = Ticket::find($id);
        $rules = [
            'department_id' => 'required',
            'subject' => 'required|min:2',
            'description' => 'required',
        ];
        $this->validate($request, $rules);

        $ticket->update([
            'department_id' => $request->department_id,
            'subject' => $request->subject,
            'status_id' => $request->status_id,
            'priority_id' => $request->priority_id,
            'description' => $request->description
        ]);
        return redirect()->route('ticket.index')->with('success', "Department has been updated!");
    }
    public function destroy($id) 
    {   
        $ticket = Ticket::find($id);
        $ticket->delete();
        return redirect()->back()->with('success', "Ticket has been deleted!");

    }
}
