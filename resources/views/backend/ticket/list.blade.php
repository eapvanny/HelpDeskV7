<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle')
    Tickets
@endsection
<!-- End block -->

@section('extraStyle')
    <style>
        /* .modal-fullscreen .modal-dialog {
                                                                                        width: 100%;
                                                                                        max-width: none;
                                                                                        height: 100%;
                                                                                        margin: 0;
                                                                                    }

                                                                                    .modal-fullscreen .modal-content {
                                                                                        height: 100%;
                                                                                        display: flex;
                                                                                        flex-direction: column;
                                                                                    }

                                                                                    .modal-body {
                                                                                        flex: 1;
                                                                                        display: flex;
                                                                                        flex-direction: column;
                                                                                    } */

        .chat-container {
            flex-grow: 1;
            overflow-y: auto;
        }

        .chat-input-container {
            position: sticky;
            bottom: 0;
            width: 100%;
            background: white;
            padding: 10px;
        }

        .photo-detail {
            width: 900px;
            height: auto;
            object-fit: cover;
            object-position: center;
            border-radius: 8px;
        }

        .photo-detail.img-fluid {
            max-width: 100%;
            height: auto;
        }

        .modal-body.img-popup {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;
        }

        .ticket-details {
            padding-left: 20px;
            font-size: 14px;
        }

        .ticket-details h4 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #333;
        }

        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            width: 100%;
            table-layout: fixed;
        }

        .table td,
        .table th {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .table th,
        .table td {
            min-width: 0;
            max-width: none;
        }
    </style>
@endsection

<!-- Page body extra class -->
@section('bodyCssClass')
@endsection
<!-- End block -->
@php
    use App\Http\Helpers\AppHelper;
@endphp
<!-- BEGIN PAGE CONTENT-->
@section('pageContent')
    <!-- Section header -->
    <section class="content-header">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ __('Dashboard') }} </a></li>
            <li class="active"> {{ __('Tickets') }} </li>
        </ol>
    </section>
    <!-- ./Section header -->
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="wrap-outter-header-title">
                <h1>
                    @if ($currentFilter === 'requests')
                        {{ __('Request Tickets') }}
                    @elseif ($currentFilter === 'accepted')
                        {{ __('Accepted Tickets') }}
                    @elseif ($currentFilter === 'rejected')
                        {{ __('Rejected Tickets') }}
                    @endif

                    <small>
                        {{ __('List') }}
                    </small>
                </h1>
                <div class="box-tools pull-right">
                    <button id="filters" class="btn btn-outline-secondary" data-bs-toggle="collapse"
                        data-bs-target="#filterContainer">
                        <i class="fa-solid fa-filter"></i> {{ __('Filter') }}
                    </button>
                    @if ($currentFilter === 'requests')
                        <a class="btn btn-info text-white" href="{{ URL::route('ticket.create') }}"><i
                                class="fa fa-plus-circle"></i> {{ __('Add New') }}</a>
                    @endif
                </div>
            </div>

            <div class="wrap-outter-box">
                <div class="box box-info">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <!-- Dynamically set the form action based on the current route -->
                                <form action="{{ route("ticket.$currentFilter") }}" method="GET" id="filterForm">
                                    <div class="wrap_filter_form @if (!$is_filter) collapse @endif"
                                        id="filterContainer">
                                        <a id="close_filter" class="btn btn-outline-secondary btn-sm">
                                            <i class="fa-solid fa-xmark"></i>
                                        </a>
                                        <div class="row">
                                            <!-- Filter fields remain the same -->
                                            <div class="col-xl-4">
                                                <div class="form-group">
                                                    <label for="Department">{{ __('Department') }}</label>
                                                    {!! Form::select('department_id', $departments, request('department_id'), [
                                                        'placeholder' => __('Select a department'),
                                                        'class' => 'form-control select2',
                                                        'id' => 'department_id',
                                                    ]) !!}
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="form-group">
                                                    <label for="status_id">{{ __('Ticket Status') }}</label>
                                                    {!! Form::select(
                                                        'status_id',
                                                        [
                                                            AppHelper::STATUS_OPEN => __(AppHelper::STATUS[AppHelper::STATUS_OPEN]),
                                                            AppHelper::STATUS_PENDING => __(AppHelper::STATUS[AppHelper::STATUS_PENDING]),
                                                            AppHelper::STATUS_RESOLVED => __(AppHelper::STATUS[AppHelper::STATUS_RESOLVED]),
                                                            AppHelper::STATUS_CLOSED => __(AppHelper::STATUS[AppHelper::STATUS_CLOSED]),
                                                        ],
                                                        request('status_id'),
                                                        ['class' => 'form-control select2', 'id' => 'status_id', 'placeholder' => __('Select a status')],
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="form-group">
                                                    <label for="priority_id">{{ __('Ticket Priority') }}</label>
                                                    {!! Form::select(
                                                        'priority_id',
                                                        [
                                                            AppHelper::PRIORITY_LOW => __(AppHelper::PRIORITY[AppHelper::PRIORITY_LOW]),
                                                            AppHelper::PRIORITY_MEDIUM => __(AppHelper::PRIORITY[AppHelper::PRIORITY_MEDIUM]),
                                                            AppHelper::PRIORITY_HIGH => __(AppHelper::PRIORITY[AppHelper::PRIORITY_HIGH]),
                                                            AppHelper::PRIORITY_URGENT => __(AppHelper::PRIORITY[AppHelper::PRIORITY_URGENT]),
                                                        ],
                                                        request('priority_id'),
                                                        ['class' => 'form-control select2', 'id' => 'priority_id', 'placeholder' => __('Select a priority')],
                                                    ) !!}
                                                </div>
                                            </div>
                                            {{-- <div class="col-xl-3">
                                                <div class="form-group">
                                                    <label for="request_statuses">{{ __('Request Status') }}</label>
                                                    {!! Form::select(
                                                        'request_statuses',
                                                        [
                                                            1 => __('Accepted'),
                                                            0 => __('Rejected'),
                                                            'null' => __('Not Confirmed'),
                                                        ],
                                                        request('request_statuses'),
                                                        ['class' => 'form-control select2', 'id' => 'request_status', 'placeholder' => __('Select a request status')],
                                                    ) !!}
                                                </div>
                                            </div> --}}
                                        </div>
                                        <div class="row">
                                            <div class="col-12 mt-2">
                                                <button id="apply_filter" class="btn btn-outline-secondary btn-sm float-end"
                                                    type="submit">
                                                    <i class="fa-solid fa-magnifying-glass"></i> {{ __('Apply') }}
                                                </button>
                                                <a href="{{ route("ticket.$currentFilter") }}"
                                                    class="btn btn-outline-secondary btn-sm float-end me-1">
                                                    <i class="fa-solid fa-xmark"></i> {{ __('Cancel') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="box-body margin-top-20">
                        <div class="table-responsive mt-4">
                            <table id="datatabble"
                                class="table table-bordered table-striped list_view_table display responsive no-wrap datatable-server"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Photo') }}</th>
                                        <th>{{ __('Department') }}</th>
                                        <th>{{ __('Staff ID') }}</th>
                                        <th>{{ __('Staff Name') }}</th>
                                        <th>{{ __('Subject') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Priority') }}</th>
                                        <th>{{ __('Request Status') }}</th>
                                        <th>{{ __('Receiver') }}</th>
                                        <th class="notexport">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal sendMessage -->
    {{-- <div class="modal modal-lg fade" id="showTicketModal" tabindex="-1" role="dialog"
        aria-labelledby="showTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="showTicketModalLabel">{{ __('Message Messenger') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" id="btnClose"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body d-flex flex-column">
                    <!-- Chat Messages (Scrollable) -->
                    <div class="chat-container flex-grow-1 overflow-auto p-3" id="chat-box">
                        <!-- Messages will be dynamically loaded here -->
                    </div>

                    <!-- Fixed Input for New Message -->
                    <div class="chat-input-container border-top p-3 bg-white">
                        <input type="hidden" id="ticket_id">
                        <div class="d-flex align-items-center">
                            <textarea id="chatMessage" class="form-control me-2" rows="1" placeholder="Type a message"></textarea>
                            <button type="button" class="btn btn-primary" id="sendMessage">{{ __('Send') }}</button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-close">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Modal photo -->
    <div class="modal modal-lg fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="photoModalLabel">{{ __('Tickets Detail') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" id="btnClose"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body img-popup">
                    <div class="row">
                        <div class="col-md-4">
                            <img id="modalPhoto" src="" class="img-fluid photo-detail" alt="Photo Detail">
                        </div>
                        <div class="col-md-8">
                            <div class="ticket-details">
                                <ul class="list-group list-group-unbordered profile-log">
                                    <li class="list-group-item size">
                                        <strong><i class="fa fa-landmark-flag"></i> {{ __('Department') }} :</strong>
                                        <span id="modalDepartment"></span>
                                    </li>
                                    <li class="list-group-item size">
                                        <strong><i class="fa fa-user"></i> {{ __('Employee Name') }}:</strong>
                                        <span id="modalEmployeeName"></span>
                                    </li>
                                    <li class="list-group-item size">
                                        <strong><i class="fa-solid fa-id-card"></i> {{ __('Staff ID') }}:</strong>
                                        <span id="modalIdCard"></span>
                                    </li>
                                    <li class="list-group-item size">
                                        <strong><i class="fa icon-subject"></i> {{ __('Subject') }}:</strong>
                                        <span id="modalSubject"></span>
                                    </li>
                                    <li class="list-group-item size">
                                        <strong><i class="fa-solid fa-paragraph"></i> {{ __('Description') }}:</strong>
                                        <span id="modalDescription"></span>
                                    </li>
                                    <li class="list-group-item size">
                                        <strong><i class="fa fa-sliders"></i> {{ __('Status') }}:</strong>
                                        <span id="modalStatus"></span>
                                    </li>
                                    <li class="list-group-item size">
                                        <strong><i class="fa fa-font-awesome"></i> {{ __('Priority') }}:</strong>
                                        <span id="modalPriority"></span>
                                    </li>
                                    <li class="list-group-item size">
                                        <strong><i class="fa-solid fa-code-pull-request"></i>
                                            {{ __('Request Status') }}:</strong>
                                        <span id="modalRequestStatus"></span>
                                    </li>
                                    <li class="list-group-item size">
                                        <strong><i class="fa fa-user"></i> {{ __('Receiver') }}:</strong>
                                        <span id="modalReceiver"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btnClose" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- /.content -->
@endsection
<!-- END PAGE CONTENT-->

<!-- BEGIN PAGE JS-->
@section('extraScript')
    <script type="text/javascript">
        $(document).ready(function() {
            // Set up CSRF token globally
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });




            var currentRoute =
                '{{ Route::currentRouteName() }}'; // e.g., ticket.requests, ticket.accepted, ticket.rejected
            var ajaxUrl = '';
            if (currentRoute === 'ticket.requests') {
                ajaxUrl = '{!! route('ticket.requests') !!}';
            } else if (currentRoute === 'ticket.accepted') {
                ajaxUrl = '{!! route('ticket.accepted') !!}';
            } else if (currentRoute === 'ticket.rejected') {
                ajaxUrl = '{!! route('ticket.rejected') !!}';
            } else {
                ajaxUrl = '{!! route('ticket.index') !!}';
            }

            var t = $('#datatabble').DataTable({
                processing: false,
                serverSide: true,
                ajax: {
                    url: ajaxUrl,
                    data: function(d) {
                        // Pass filter parameters if they exist
                        d.department_id = $('#department_id').val();
                        d.status_id = $('#status_id').val();
                        d.priority_id = $('#priority_id').val();
                        d.request_statuses = $('#request_statuses').val();
                    }
                },
                pageLength: 10,
                responsive: false,
                scrollX: true,
                scrollCollapse: true,
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'photo',
                        name: 'photo'
                    },
                    {
                        data: 'department',
                        name: 'department'
                    },
                    {
                        data: 'id_card',
                        name: 'id_card'
                    },
                    {
                        data: 'employee_name',
                        name: 'employee_name'
                    },
                    {
                        data: 'subject',
                        name: 'subject'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'priority',
                        name: 'priority'
                    },
                    {
                        data: 'request_status',
                        name: 'request_status'
                    },
                    {
                        data: 'receiver',
                        name: 'receiver'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    }
                ]
            });
            // $('#filterForm').on('submit', function(e) {
            //     e.preventDefault();
            //     t.ajax.reload();
            // });

            // Clear modal content on close
            $(document).on('click', '#btn-close, #btnClose', function() {
                $('#showTicketModal').modal('hide');
                $('#chat-box').html('');
                $('#ticket_id').val('');
                $("#chatMessage").val("");
            });

            $('#close_filter').click(function() {
                $("#filters").trigger('click');
            });

            $(document).on('click', '.btn-accept', function() {
                var ticketId = $(this).data('id');
                var $btnGroup = $(this).closest('.btn-group');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to accept this ticket?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, accept it!',
                    cancelButtonText: 'No, cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('ticket.update-status', '__ID__') }}'.replace(
                                '__ID__', ticketId),
                            method: 'POST',
                            data: {
                                request_status: 1
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Accepted!',
                                        text: 'The ticket has been accepted.',
                                        icon: 'success',
                                        timer: 1850, // Auto-close after 3 seconds
                                        showConfirmButton: false // Hide OK button
                                    });
                                    // t.ajax.reload();
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1800);
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Error updating status: ' + xhr.responseText,
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            // Handle Unaccept button click
            $(document).on('click', '.btn-unaccept', function() {
                var ticketId = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to unaccept this ticket?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, unaccept it!',
                    cancelButtonText: 'No, cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('ticket.update-status', '__ID__') }}'.replace(
                                '__ID__', ticketId),
                            method: 'POST',
                            data: {
                                request_status: null
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Unaccepted!',
                                        text: 'The ticket has been unaccepted.',
                                        icon: 'success',
                                        timer: 1850, // Auto-close after 3 seconds
                                        showConfirmButton: false // Hide OK button
                                    });
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1800);
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Error updating status: ' + xhr.responseText,
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            // Handle Reject button click
            $(document).on('click', '.btn-reject', function() {
                var ticketId = $(this).data('id');
                var $btnGroup = $(this).closest('.btn-group');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to reject this ticket?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, reject it!',
                    cancelButtonText: 'No, cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('ticket.update-status', '__ID__') }}'.replace(
                                '__ID__', ticketId),
                            method: 'POST',
                            data: {
                                request_status: 0
                            },
                            success: function(response) {
                                if (response.success) {
                                    $btnGroup.html(
                                        '<span style="background-color: #dd4b39; padding: 2px 5px; border-radius: 3px; color: white; cursor: pointer;">Rejected</span>'
                                    );
                                    Swal.fire({
                                        title: 'Rejected!',
                                        text: 'The ticket has been rejected.',
                                        icon: 'success',
                                        timer: 1850, // Auto-close after 3 seconds
                                        showConfirmButton: false // Hide OK button
                                    });
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1850);
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Error updating status: ' + xhr.responseText,
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            // Handle Unreject button click
            $(document).on('click', '.btn-unreject', function() {
                var ticketId = $(this).data('id');
                var $btnGroup = $(this).closest('.btn-group');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to unreject this ticket?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, unreject it!',
                    cancelButtonText: 'No, cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('ticket.update-status', '__ID__') }}'.replace(
                                '__ID__', ticketId),
                            method: 'POST',
                            data: {
                                request_status: null
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Unrejected!',
                                        text: 'The ticket rejection has been undone.',
                                        icon: 'success',
                                        timer: 1850, // Auto-close after 3 seconds
                                        showConfirmButton: false // Hide OK button
                                    });
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1800);
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Error updating status: ' + xhr.responseText,
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.img-detail', function() {
                var ticketId = $(this).data('id');
                $.ajax({
                    url: '/ticket/' + ticketId,
                    method: 'GET',
                    success: function(response) {
                        var ticket = response.ticket;
                        $('#modalPhoto').attr('src', ticket.photo ? '/storage/' + ticket.photo :
                            '/images/avatar.png');
                        $('#modalSubject').text(ticket.subject);
                        $('#modalDepartment').text(ticket.department_name);
                        $('#modalIdCard').text(ticket.id_card);
                        $('#modalEmployeeName').text(ticket.employee_name);
                        $('#modalDescription').text(ticket.description);
                        $('#modalStatus').text(ticket.status_text);
                        $('#modalPriority').text(ticket.priority_text);
                        $('#modalRequestStatus').text(ticket.request_status_text);
                        $('#modalReceiver').text(ticket.receiver);
                        $('#photoModal').modal('show');
                    },
                    error: function(xhr) {
                        console.log('Error:', xhr);
                        alert('Failed to load ticket details.');
                    }
                });
            });
            $(document).on('click', '.btnClose', function() {
                $('#photoModal').modal('hide');
                $('.img-popup').val('');
            });
            //delete grade_level
            $('#datatabble').delegate('.delete', 'click', function(e) {
                let action = $(this).attr('href');
                console.log()
                $('#myAction').attr('action', action);
                e.preventDefault();
                swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this record!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dd4848',
                    cancelButtonColor: '#8f8f8f',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it'
                }).then((result) => {
                    if (result.value) {
                        $('#myAction').submit();
                    }
                });
            });
        });
    </script>
@endsection
<!-- END PAGE JS-->
