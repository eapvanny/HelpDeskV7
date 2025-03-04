<?php

namespace App\Http\Controllers;

use App\Events\TicketRequest;
use App\Models\Department;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Helpers\AppHelper;
use App\Models\ChatMessage;
use Illuminate\Support\Carbon;
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
    private $indexof = 1;

    private function getTicketsByRequestStatus(Request $request, $requestStatus = null)
    {
        $query = Ticket::with(['department', 'user'])->where('request_status', $requestStatus)->orderBy('id', 'desc');
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
        // if ($request->has('request_statuses') && $request->request_status !== '') {
        //     $is_filter = true;

        //     if ($request->request_statuses === 'null') {
        //         $query->whereNull('request_status');
        //     } else {
        //         $query->where('request_status', $request->request_statuses);
        //     }
        // }
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
                ->addColumn('date', function ($data) {
                    return $data->date ? Carbon::parse($data->date)->format('d-M-Y h:i A') : 'N/A';
                })
                ->addColumn('status', function ($data) {
                    $statusColors = [
                        1 => '#3c8dbc',
                        2 => '#e5c086',
                        3 => '#549f54',
                        4 => 'grey',
                    ];
                    $status = AppHelper::STATUS[$data->status_id] ?? 'Unknown';
                    $color = $statusColors[$data->status_id] ?? '#000000';
                    return sprintf(
                        '<span style="background-color: %s; padding: 7px 6px; color: white; border-radius: 3px">%s</span>',
                        $color,
                        __($status)
                    );
                })
                ->addColumn('priority', function ($data) {
                    $priorityColors = [
                        1 => 'grey',
                        2 => '#ffc107',
                        3 => '#fd7e14',
                        4 => '#dc3545',
                    ];
                    $priority = AppHelper::PRIORITY[$data->priority_id] ?? 'Unknown';
                    $color = $priorityColors[$data->priority_id] ?? '#000000';
                    $textColor = $color === '#ffc107' ? 'white' : 'white';
                    return sprintf(
                        '<span style="background-color: %s; padding: 7px 6px; color: %s; border-radius: 3px">%s</span>',
                        $color,
                        $textColor,
                        __($priority)
                    );
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
                            return '<span style="background-color: #3c8dbc; ' . $disabledStyle . '">' . __('Accepted') . '</span>';
                        }
                        return '<div class="btn-group" style="gap: 5px;">' .
                            '<span class="btn-unaccept" data-id="' . $data->id . '" style="background-color: #3c8dbc; ' . $clickableStyle . '">' . __('Accepted') . '</span>' .
                            '</div>';
                    } elseif ($data->request_status === 0) {
                        $style = $isNotSuperAdminOrAdminSupport ? $disabledStyle : $clickableStyle;
                        $class = $isNotSuperAdminOrAdminSupport ? '' : ' class="btn-unreject"';
                        return '<span' . $class . ' data-id="' . $data->id . '" style="background-color: #dd4b39; ' . $style . '">' . __('Rejected') . '</span>';
                    } elseif ($data->request_status === null) {
                        if ($isNotSuperAdminOrAdminSupport) {
                            return '<span style="background-color: rgb(211, 211, 211); padding: 4px 5px; border-radius: 3px; color: #666; cursor: not-allowed;">Sent</span>';
                        }
                        return '<div class="btn-group" style="gap: 5px;">' .
                            '<span class="btn-accept" data-id="' . $data->id . '" style="background-color: #3c8dbc; ' . $clickableStyle . '">' . __('Accept') . '</span>' .
                            '<span class="btn-reject" data-id="' . $data->id . '" style="background-color: #dd4b39; ' . $clickableStyle . '">' . __('Reject') . '</span>' .
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
                    if (auth()->user()->can('delete ticket') && auth()->user()->role_id == AppHelper::USER_SUPER_ADMIN) {
                        $button .= '<a href="' . route('ticket.destroy', $data->id) . '" class="btn btn-danger btn-sm delete" title="Delete"><i class="fa fa-fw fa-trash"></i></a>';
                        $actions = true;
                    }
                    if (!$actions) {
                        $button .= '<span style="font-weight:bold; color:red;">No Action</span>';
                    }
                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['photo', 'status', 'action', 'request_status', 'priority'])
                ->make(true);
        }

        $departments = Department::pluck('name', 'id')->toArray();
        $currentFilter = match ($requestStatus) {
            null => 'requests',
            1 => 'accepted',
            0 => 'rejected',
            default => 'all',
        };

        return view('backend.ticket.list', compact('is_filter', 'departments', 'currentFilter'));
    }

    // Sidebar-specific methods
    public function getRequestTickets(Request $request)
    {
        return $this->getTicketsByRequestStatus($request, null); // null for "not accepted or rejected"
    }

    public function getAcceptedTickets(Request $request)
    {
        return $this->getTicketsByRequestStatus($request, 1); // 1 for "accepted"
    }

    public function getRejectedTickets(Request $request)
    {
        return $this->getTicketsByRequestStatus($request, 0); // 0 for "rejected"
    }

    // Default index method
    public function index(Request $request)
    {
        return $this->getTicketsByRequestStatus($request); // No specific request_status filter by default
    }
    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $newStatus = $request->input('request_status');

        $ticket->request_status = $newStatus;
        if ($newStatus == null) {
            $ticket->receiver = null;
            $ticket->status_id = AppHelper::STATUS_OPEN;
        } elseif ($newStatus == 1) {
            $ticket->receiver = auth()->user()->name;
            $ticket->status_id = AppHelper::STATUS_PENDING;
        } elseif ($newStatus == 0) {
            $ticket->receiver = auth()->user()->name;
            $ticket->status_id = AppHelper::STATUS_CLOSED;
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
        $ticket->priority_text = AppHelper::PRIORITY[$ticket->priority_id] ?? 'Unknown';

        if ($ticket->request_status === 1) {
            $ticket->request_status_text = 'Accepted';
        } elseif ($ticket->request_status === 0) {
            $ticket->request_status_text = 'Rejected';
        } elseif ($ticket->request_status === null) {
            $ticket->request_status_text = 'Unknown';
        }

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
            'description' => $request->description,
            'date' => Carbon::now(),
        ];

        if ($request->status_id == AppHelper::STATUS_OPEN) {
            $ticketData['request_status'] = null;
        } elseif (in_array($request->status_id, [AppHelper::STATUS_PENDING, AppHelper::STATUS_RESOLVED])) {
            $ticketData['request_status'] = 1;
            $ticketData['receiver'] = auth()->user()->name;
        } elseif ($request->status_id == AppHelper::STATUS_CLOSED) {
            $ticketData['request_status'] = 0;
        }

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '_' . md5($file->getClientOriginalName()) . '.' . $file->extension();
            $filePath = 'uploads/' . $fileName;
            Storage::put($filePath, file_get_contents($file));
            $ticketData['photo'] = $filePath;
        } else {
            $ticketData['photo'] = $request->oldphoto;
        }

        $ticket = Ticket::create($ticketData);

        // Fire the event with a meaningful message
        event(new TicketRequest("A new ticket has been created by " . auth()->user()->name));

        return redirect()->route('ticket.index')->with('success', "Ticket has been created!");
    }
    public function edit(Request $request, $id)
    {
        $ticket = Ticket::find($id);
        $departments = Department::pluck('name', 'id');

        if (!$ticket) {
            return redirect()->route('ticket.index');
        }

        // dd(auth()->user()->role_id, $ticket->request_status);

        // Check if user is EMPLOYEE and ticket is accepted
        if (auth()->user()->role_id === AppHelper::USER_EMPLOYEE) {
            if ($ticket->request_status === 1) {
                return redirect()->route('ticket.index')
                    ->with('error', "This ticket can't be edited because it has already been accepted.");
            } elseif ($ticket->request_status === 0) {
                return redirect()->route('ticket.index')
                    ->with('error', "This ticket can't be edited because it has already been rejected.");
            }
        }
        // Optional: Check permission
        if (!auth()->user()->can('update ticket')) {
            return redirect()->route('ticket.index')
                ->with('error', 'You do not have permission to edit tickets.');
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
            'date' => Carbon::now('Asia/Phnom_Penh'),

        ];

        if ($request->status_id == AppHelper::STATUS_OPEN) {
            $ticketData['request_status'] = null;
        } elseif ($request->status_id == AppHelper::STATUS_PENDING || $request->status_id == AppHelper::STATUS_RESOLVED) {
            $ticketData['request_status'] = 1;
        } elseif ($request->status_id == AppHelper::STATUS_CLOSED) {
            $ticketData['request_status'] = 0;
        }


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

    public function getNotifications()
    {
        $totalTickets = Ticket::whereNull('request_status')
            ->whereNotIn('status_id', [AppHelper::STATUS_CLOSED, AppHelper::STATUS_RESOLVED])
            ->count();

        $tickets = Ticket::whereNull('request_status')
            ->whereNotIn('status_id', [AppHelper::STATUS_CLOSED, AppHelper::STATUS_RESOLVED])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['id', 'subject', 'description']);

        return response()->json([
            'totalTickets' => $totalTickets,
            'tickets' => $tickets
        ]);
    }
}
