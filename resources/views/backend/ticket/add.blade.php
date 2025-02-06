<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle') Tickets @endsection
<!-- End block -->

<!-- Page body extra class -->
@section('bodyCssClass') @endsection
<!-- End block -->
@section('extraStyle')
    <style>
        fieldset .form-group{
            margin-bottom: 0px;
        }
        fieldset .iradio .error,fieldset .icheck .error{
            display: none !important;
        }

        @media (max-width: 600px) {
            .display-flex{
                display: inline-flex;
            }
        }
        @media (max-width: 768px) {
            .display-flex{
                display: inline-flex;
            }
        }
        .checkbox, .radio{
            display: inline-block;
        }
        .checkbox{
            margin-left: 10px;
        }
        legend {
            margin: 0;
            width: unset;
            font-weight: 700;
            font-size: 14px;
            color: #0059a1;
            display: block;
            padding-inline-start: 2px;
            padding-inline-end: 2px;
            border-width: initial;
            border-style: none;
            border-color: initial;
            border-image: initial;
        }
        fieldset {
            padding: 1em 0.625em 1em;
            border: 1px solid #9a9a9a;
            margin: 2px 2px;
            padding: .35em .625em .75em;
            margin-top: 4px;
        }
    </style>
@endsection
<!-- BEGIN PAGE CONTENT-->
@section('pageContent')
    <!-- Section header -->
    <section class="content-header">
        <ol class="breadcrumb">
            <li><a href="{{URL::route('dashboard.index')}}"><i class="fa fa-dashboard"></i> {{ __('Dashboard') }} </a></li>
            <li><a href="{{URL::route('ticket.index')}}"> {{ __('Ticket') }} </a></li>
            <li class="active">@if($ticket) Update @else {{ __('Add') }} @endif</li>
        </ol>
    </section>
    <!-- ./Section header -->
    <!-- Main content -->
    <section class="content">
                
        <form novalidate id="entryForm" action="@if($ticket) {{URL::Route('ticket.update', $ticket->id)}} @else {{URL::Route('ticket.store')}} @endif" method="post" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
                <div class="col-md-12">
                    <div class="wrap-outter-header-title">
                        <h1>
                            {{ __('ticket') }}
                            <small>@if($ticket) Update @else {{ __('Add New') }} @endif</small>
                        </h1>

                        <div class="box-tools pull-right">
                            <a href="{{URL::route('ticket.index')}}" class="btn btn-default">Cancel</a>
                            <button type="submit" class="btn btn-info pull-right text-white"><i class="fa @if($ticket) fa-refresh @else fa-plus-circle @endif"></i> @if($ticket) Update @else Add @endif</button>
                        </div>
                    </div>
                </div>
            </div>    
            <div class="wrap-outter-box">
            <input id="org_detail" type="hidden" name="org_detail" value="">
            <div class="box-body">
                @csrf
                @if($ticket)  {{ method_field('PATCH') }} @endif
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label for="subject"> {{ __('Subject') }} <span class="text-danger">*</span></label>
                            <input autofocus type="text" class="form-control" name="subject" placeholder="subject" value="@if($ticket){{ $ticket->subject }}@else{{old('subject')}}@endif" required minlength="2" maxlength="255">
                            <span class="fa fa-info form-control-feedback"></span>
                            <span class="text-danger">{{ $errors->first('subject') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-xl-3"> 
                        <div class="form-group has-feedback">
                            <label for="department_id"> {{ __('Department') }} <span class="text-danger">*</span>
                                <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Select Department"></i>
                            </label>
                            {!! Form::select('department_id', $departments, old('department_id', optional($ticket)->department_id), [
                                'placeholder' => __('Pick a department'),
                                'id' => 'department_id',
                                'name' => 'department_id',
                                'class' => 'form-control select2',
                                'required' => true
                            ]) !!}
                            <span class="form-control-feedback"></span>
                            <span class="text-danger">{{ $errors->first('department_id') }}</span>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-xl-3">
                        <div class="form-group has-feedback">
                            <label for="status"> {{ __('Status') }} <span class="text-danger">*</span></label>
                            {{-- @php
                                $statusKey = $ticketOption ? $ticketOption->status_id : null;
                            @endphp --}}
                            {!! Form::select('status', 
                            [
                                AppHelper::STATUS_OPEN => __(AppHelper::STATUS[AppHelper::STATUS_OPEN]),
                                AppHelper::STATUS_PENDING => __(AppHelper::STATUS[AppHelper::STATUS_PENDING]),
                                AppHelper::STATUS_RESOLVED => __(AppHelper::STATUS[AppHelper::STATUS_RESOLVED]),
                                AppHelper::STATUS_CLOSED => __(AppHelper::STATUS[AppHelper::STATUS_CLOSED]),
                            ],
                            old('status'), 
                            [
                                'class' => 'form-control select2',
                                'required' => 'true',
                                'id' => 'status',
                                'name' => 'status' 
                            ]) !!}
                        </div>
                    </div>                    
                    <div class="col-md-3 col-xl-3">
                        <div class="form-group has-feedback">
                            <label for="priority"> {{ __('Priority') }} <span class="text-danger">*</span></label>
                            @php
                                $priorityKey = $ticket ? $ticket->priority : null;
                            @endphp
                            {!! Form::select('priority', 
                            [
                                AppHelper::PRIORITY_LOW => __(AppHelper::PRIORITY[AppHelper::PRIORITY_LOW]),
                                AppHelper::PRIORITY_MEDUIM => __(AppHelper::PRIORITY[AppHelper::PRIORITY_MEDUIM]),
                                AppHelper::PRIORITY_HIGH => __(AppHelper::PRIORITY[AppHelper::PRIORITY_HIGH]),
                                AppHelper::PRIORITY_URGENT => __(AppHelper::PRIORITY[AppHelper::PRIORITY_URGENT]),
                            ]
                            , old('priority', $priorityKey), 
                            [
                                'class' => 'form-control select2',
                                'required' => 'true',
                                'id' => 'priority',
                                'name' => 'priority'
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
<!-- END PAGE CONTENT-->

<!-- BEGIN PAGE JS-->
@section('extraScript')
    <script type="text/javascript">
        $(document).ready(function () {
           
        });
    </script>
@endsection
<!-- END PAGE JS-->
