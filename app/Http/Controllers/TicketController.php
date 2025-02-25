<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Helpers\AppHelper;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view ticket', ['only' => ['index']]);
        $this->middleware('permission:create ticket', ['only' => ['create', 'store']]);
        $this->middleware('permission:update ticket', ['only' => ['update', 'edit']]);
        $this->middleware('permission:delete ticket', ['only' => ['destroy']]);
    }
    public $indexof = 1;
    public function index(Request $request)
    {
        $query = Ticket::with(['department', 'user']);
        $is_filter = false;
        if (auth()->user()->role_id == AppHelper::USER_EMPLOYEE) {
            $query->where('user_id', auth()->id());
        }

        if ($request->has('department_id') && $request->department_id != '') {
            $is_filter = true;
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('status_id') && $request->status_id != '') {
            $is_filter = true;
            $query->where('status_id', $request->status_id);
        }

        if ($request->has('priority_id') && $request->priority_id != '') {
            $is_filter = true;
            $query->where('priority_id', $request->priority_id);
        }
        if ($request->ajax()) {
            $tickets = $query->get();

            return DataTables::of($tickets)
                ->addColumn('id', function ($data) {
                    return $this->indexof++;
                })
                ->addColumn('photo', function ($data) {
                    return '<img class="img-responsive center" 
                                style="height: 35px; width: 35px; object-fit: cover; border-radius: 50%;" 
                                src="' . ($data->photo ? asset('storage/' . $data->photo) : asset('images/avatar.png')) . '">';
                })
                ->addColumn('subject', function ($data) {
                    return $data->subject;
                })
                ->addColumn('department', function ($data) {
                    $language = session('user_lang', 'kh');
                    return $language == 'en' ? $data->department->name_in_latin : $data->department->name;
                })
                ->addColumn('id_card', function ($data) {
                    return __($data->id_card);
                })
                ->addColumn('employee_name', function ($data) {
                    return __($data->employee_name);
                })
                ->addColumn('description', function ($data) {
                    return __($data->description);
                })
                ->addColumn('status', function ($data) {
                    return AppHelper::STATUS[$data->status_id] ?? 'Unknown';
                })
                ->addColumn('priority', function ($data) {
                    return AppHelper::PRIORITY[$data->priority_id] ?? 'Unknown';
                })
                ->addColumn('request_status', function ($data) {
                    $isNotSuperAdminOrAdminSupport = auth()->check() &&
                        auth()->user()->role_id !== AppHelper::USER_SUPER_ADMIN &&
                        auth()->user()->role_id !== AppHelper::USER_ADMIN_SUPPORT;
                    $baseStyle = 'padding: 4px 5px; border-radius: 3px; color: white;';
                    $disabledStyle = $baseStyle . ' cursor: not-allowed;';
                    $clickableStyle = $baseStyle . ' cursor: pointer;';

                    if ($data->request_status === 1) {
                        if ($isNotSuperAdminOrAdminSupport) {
                            return '<span style="background-color: #3c8dbc; ' . $disabledStyle . '">Accepted</span>';
                        }
                        return '<div class="btn-group" style="gap: 5px;">' .
                            '<span class="btn-unaccept" data-id="' . $data->id . '" style="background-color: #3c8dbc; ' . $clickableStyle . '">Accepted</span>' .
                            '</div>';
                    } elseif ($data->request_status === 0) {
                        $style = $isNotSuperAdminOrAdminSupport ? $disabledStyle : $clickableStyle;
                        $class = $isNotSuperAdminOrAdminSupport ? '' : ' class="btn-unreject"';
                        return '<span' . $class . ' data-id="' . $data->id . '" style="background-color: #dd4b39; ' . $style . '">Rejected</span>';
                    } elseif ($data->request_status === null) {
                        if ($isNotSuperAdminOrAdminSupport) {
                            return '<span style="background-color: rgb(211, 211, 211); padding: 4px 5px; border-radius: 3px; color: #666; cursor: not-allowed;">Sent</span>';
                        }
                        return '<div class="btn-group" style="gap: 5px;">' .
                            '<span class="btn-accept" data-id="' . $data->id . '" style="background-color: #3c8dbc; ' . $clickableStyle . '">Accept</span>' .
                            '<span class="btn-reject" data-id="' . $data->id . '" style="background-color: #dd4b39; ' . $clickableStyle . '">Reject</span>' .
                            '</div>';
                    }
                    return '<span>Unknown Status</span>';
                })
                ->addColumn('receiver', function ($data) {
                    return $data->receiver ?? '';
                })
                ->addColumn('action', function ($data) {
                    $button = '<div class="change-action-item">';
                    $actions = false;
                    if (auth()->user()->can('show ticket')) {
                        $button .= '<span class="change-action-item"><a href="javascript:void(0);" class="btn btn-primary btn-sm img-detail" data-id="' . $data->id . '" title="Show" data-bs-toggle="modal"><i class="fa fa-fw fa-eye"></i></a></span>';
                        $actions = true;
                    }
                    if (auth()->user()->can('update ticket')) {
                        $button .= '<a title="Edit" href="' . route('ticket.edit', $data->id) . '" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                        $actions = true;
                    }
                    if (auth()->user()->can('delete ticket')) {
                        $button .= '<a href="' . route('ticket.destroy', $data->id) . '" class="btn btn-danger btn-sm delete" title="Delete"><i class="fa fa-fw fa-trash"></i></a>';
                        $actions = true;
                    }
                    if (!$actions) {
                        $button .= '<span style="font-weight:bold; color:red;">No Action</span>';
                    }
                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['photo', 'status', 'action', 'request_status'])
                ->make(true);
        }

        $departments = Department::pluck('name', 'id')->toArray();
        return view('backend.ticket.list', compact('is_filter', 'departments'));
    }

    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $newStatus = $request->input('request_status');

        $ticket->request_status = $newStatus;
        if ($newStatus === null) {
            $ticket->receiver = null;
        } elseif ($newStatus == 1 || $newStatus == 0) {
            $ticket->receiver = auth()->user()->name;
        }
        $ticket->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }
    public function create()
    {

        $departments = Department::pluck('name', 'id');
        $ticket = null;
        return view('backend.ticket.add', compact('departments', 'ticket'));
    }

    public function show($id)
    {
        $ticket = Ticket::with('department')->find($id);

        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }

        $ticket->status_text = AppHelper::STATUS[$ticket->status_id] ?? 'Unknown';

        $language = session('user_lang', 'kh');
        $ticket->department_name = $ticket->department
            ? ($language === 'en' ? $ticket->department->name_in_latin : $ticket->department->name)
            : 'N/A';

        return response()->json(['ticket' => $ticket]);
    }

    // public function getMessages($ticket_id)
    // {
    //     $lastMessageId = request('lastMessageId', 0);

    //     $messages = ChatMessage::where('ticket_id', $ticket_id)
    //         ->where('id', '>', $lastMessageId)
    //         ->with('user')
    //         ->orderBy('created_at', 'asc')
    //         ->get();

    //     $messages->map(function ($message) {
    //         $message->user_photo_url = $message->user->photo_url;
    //         return $message;
    //     });

    //     return response()->json($messages);
    // }

    // public function sendMessage(Request $request)
    // {
    //     $request->validate([
    //         'ticket_id' => 'required|exists:tickets,id',
    //         'message' => 'required|string',
    //     ]);

    //     $message = ChatMessage::create([
    //         'ticket_id' => $request->ticket_id,
    //         'user_id' => Auth::id(),
    //         'message' => $request->message,
    //     ]);

    //     return response()->json($message);
    // }




    public function store(Request $request)
    {
        $rules = [
            'department_id' => 'required',
            'photo' => 'mimes:jpeg,jpg,png|max:2000|dimensions:min_width=50,min_height=50',
            'subject' => 'required|min:2',
            'description' => 'required',
            'id_card' => 'required|min:3',
            'employee_name' => 'required',
        ];
        $this->validate($request, $rules);

        $ticketData = [
            'user_id' => auth()->id(),
            'department_id' => $request->department_id,
            'id_card' => $request->id_card,
            'employee_name' => $request->employee_name,
            'subject' => $request->subject,
            'status_id' => $request->status_id,
            'priority_id' => $request->priority_id,
            'description' => $request->description
        ];

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '_' . md5($file->getClientOriginalName()) . '.' . $file->extension();
            $filePath = 'uploads/' . $fileName;
            Storage::put($filePath, file_get_contents($file));
            $ticketData['photo'] = $filePath;
        }

        Ticket::create($ticketData);

        return redirect()->route('ticket.index')->with('success', "Tickets has been created!");
    }
    public function edit($id)
    {
        $ticket = Ticket::find($id);
        $departments = Department::pluck('name', 'id');
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
        $ticket = Ticket::findOrFail($id); // Ensures ticket exists
        $rules = [
            'department_id' => 'required',
            'photo' => 'nullable|mimes:jpeg,jpg,png|max:2000|dimensions:min_width=50,min_height=50',
            'subject' => 'required|min:2',
            'description' => 'required',
            'id_card' => 'required|min:3',
            'employee_name' => 'required',
        ];
        $this->validate($request, $rules);

        $ticketData = [
            'department_id' => $request->department_id,
            'id_card' => $request->id_card,
            'employee_name' => $request->employee_name,
            'subject' => $request->subject,
            'status_id' => $request->status_id,
            'priority_id' => $request->priority_id,
            'description' => $request->description,
        ];

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '_' . md5($file->getClientOriginalName()) . '.' . $file->extension();
            $filePath = $file->storeAs('uploads', $fileName, 'public'); 

            if (!empty($ticket->photo) && Storage::disk('public')->exists($ticket->photo)) {
                Storage::disk('public')->delete($ticket->photo);
            }

            $ticketData['photo'] = $filePath;
        } else {
            $ticketData['photo'] = $ticket->photo;
        }

        // Update ticket
        $ticket->update($ticketData);

        return redirect()->route('ticket.index')->with('success', "Ticket has been updated!");
    }
    public function destroy($id)
    {
        $ticket = Ticket::find($id);
        $ticket->delete();
        return redirect()->back()->with('success', "Ticket has been deleted!");
    }
}
