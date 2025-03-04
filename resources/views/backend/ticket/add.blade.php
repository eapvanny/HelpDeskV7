<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle')
    Tickets
@endsection
<!-- End block -->

<!-- Page body extra class -->
@section('bodyCssClass')
@endsection
<!-- End block -->
@section('extraStyle')
    <style>
        fieldset {
            padding: 1em 0.625em 1em;
            border: 1px solid #ddd;
            margin: 10px 0;
            padding: 0.35em 0.625em 0.75em;
            border-radius: 10px;
        }

        fieldset .form-group {
            margin-bottom: 0px;
        }

        .list-radio .form-group.has-error .help-block {
            position: absolute;
            width: 300px;
            bottom: -18px;
        }

        .list-time-schedule .error.help-block {
            position: absolute;
            width: 300px;
            bottom: -18px;
            color: #dd4b39;
            font-size: 12px;
        }

        @media (max-width: 600px) {
            .display-flex {
                display: inline-flex;
            }
        }

        @media (max-width: 768px) {
            .display-flex {
                display: inline-flex;
            }
        }

        fieldset>#student-photo {
            overflow: hidden;
            cursor: pointer;
            width: 100%;
            height: 340px;
            background-color: #f5f5f5;
        }

        fieldset>#student-photo>#btn-upload-photo {
            min-width: 100px;
            min-height: 100px;
            background-color: #ddd;
            font-size: 25px;
        }

        fieldset>#photo-preview {
            height: 250px;
            width: 250px;
            position: absolute;
            object-fit: cover;
        }

        .fly_action_btn {
            z-index: 2;
        }
    </style>
@endsection
@php
    use App\Http\Helpers\AppHelper;
@endphp
<!-- BEGIN PAGE CONTENT-->
@section('pageContent')
    <!-- Section header -->
    <section class="content-header">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ __('Dashboard') }} </a></li>
            <li><a href="{{ URL::route('ticket.index') }}"> {{ __('Ticket') }} </a></li>
            <li class="active">
                @if ($ticket)
                    Update
                @else
                    {{ __('Add') }}
                @endif
            </li>
        </ol>
    </section>
    <!-- ./Section header -->
    <!-- Main content -->
    <section class="content">

        <form novalidate id="entryForm"
            action="@if ($ticket) {{ URL::Route('ticket.update', $ticket->id) }} @else {{ URL::Route('ticket.store') }} @endif"
            method="post" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
                <div class="col-md-12">
                    <div class="wrap-outter-header-title">
                        <h1>
                            {{ __('Ticket') }}
                            <small>
                                @if ($ticket)
                                    Update
                                @else
                                    {{ __('Add New') }}
                                @endif
                            </small>
                        </h1>

                        <div class="box-tools pull-right">
                            <a href="{{ URL::route('ticket.index') }}" class="btn btn-default">{{ __('Cancel') }}</a>
                            <button type="submit" class="btn btn-info pull-right text-white"><i
                                    class="fa @if ($ticket) fa-refresh @else fa-plus-circle @endif"></i>
                                @if ($ticket)
                                    {{ __('Update') }}
                                @else
                                    {{ __('Add') }}
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wrap-outter-box">
                <input id="org_detail" type="hidden" name="org_detail" value="">
                <div class="box-body">
                    @csrf
                    @if ($ticket)
                        @method('PUT')
                    @endif
                    <div class="row">
                        <div class="form-group has-feedback">
                            <div class="row">
                                <div class="row-span-6 col-md-6 mt-4">
                                    <div class="form-group has-feedback position-relative">
                                        <input type="file" id="photo" name="photo" style="display: none" accept="image/*">
                                        <button type="button" class="btn btn-light text-secondary fs-5 position-absolute d-none m-2 end-0 z-1" id="btn-remove-photo"><i class="fa-solid fa-trash"></i></button>
                                        <fieldset id="photo-upload" class="p-0 d-flex align-items-center justify-content-center z-0 position-relative">
                                            <img class="rounded mx-auto d-block @if(!old('oldphoto') && !old('img-preview') && !isset($ticket)){{'d-none'}}@endif z-1" id="photo-preview" name="oldphoto" src="@if(optional($ticket)->photo){{asset('storage/' . $ticket->photo)}}@else{{old('oldphoto')}}@endif" alt="photo">
                                            <input type="hidden" id="img-preview" name="oldphoto" value="@if(optional($ticket)->photo){{asset($ticket->photo)}}@endif">
                                            <div class="d-flex align-items-center justify-content-center bg-transparent z-2  @if(!old('img-preview')){{'opacity-100'}} @else {{'opacity-25'}}@endif" id="student-photo">
                                                <button class="btn p-3 rounded-circle" id="btn-upload-photo" type="button" onclick="" >
                                                    <i class="fa-solid fa-camera-retro"></i>
                                                </button>
                                            </div>
                                            <label class="position-absolute bottom-0 text-center w-100 mb-2" for="photo">
                                                {{__('Employee photos only accept jpg, png, jpeg images')}}
                                            </label>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xl-12">
                                            <div class="form-group has-feedback">
                                                <label for="department_id"> {{ __('Department') }} <span
                                                        class="text-danger">*</span>
                                                    <i class="fa fa-question-circle" data-toggle="tooltip"
                                                        data-placement="bottom" title="Select Department"></i>
                                                </label>
                                                {!! Form::select('department_id', $departments, old('department_id', optional($ticket)->department_id), [
                                                    'placeholder' => __('Select a department'),
                                                    'id' => 'department_id',
                                                    'name' => 'department_id',
                                                    'class' => 'form-control select2',
                                                    'required' => true,
                                                ]) !!}
                                                <span class="form-control-feedback"></span>
                                                <span class="text-danger">{{ $errors->first('department_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xl-12">
                                            <div class="form-group has-feedback">
                                                <label for="id_card"> {{ __('Staff ID') }} <span
                                                        class="text-danger">{{__('(You can not changed)')}}*</span></label>
                                                <input type="text" class="form-control" name="id_card"
                                                    placeholder="id_card"
                                                    value="{{auth()->user()->staff_id_card}}" readonly>
                                                <span class="fa fa-info form-control-feedback"></span>
                                                <span class="text-danger">{{ $errors->first('id_card') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xl-12">
                                            <div class="form-group has-feedback">
                                                <label for="employee_name"> {{ __('Employee Name') }} <span
                                                        class="text-danger">{{__('(You can not changed)')}}*</span></label>
                                                <input type="text" class="form-control" name="employee_name"
                                                    placeholder="name"
                                                    value="{{auth()->user()->name}}" readonly>
                                                <span class="fa fa-info form-control-feedback"></span>
                                                <span class="text-danger">{{ $errors->first('employee_name') }}</span>
                                            </div>
                                        </div>
                                        @php
                                            $isAdmin =
                                                auth()->user()->role_id === AppHelper::USER_SUPER_ADMIN ||
                                                auth()->user()->role_id === AppHelper::USER_ADMIN_SUPPORT;
                                        @endphp
                                        <div class="col-lg-6 col-md-6 col-xl-6 {{ !$isAdmin ? 'd-none' : '' }}">
                                            <div class="form-group has-feedback">
                                                <label for="status_id"> {{ __('Status') }} <span
                                                        class="text-danger">*</span></label>
                                                {!! Form::select(
                                                    'status_id',
                                                    [
                                                        AppHelper::STATUS_OPEN => __(AppHelper::STATUS[AppHelper::STATUS_OPEN]),
                                                        AppHelper::STATUS_PENDING => __(AppHelper::STATUS[AppHelper::STATUS_PENDING]),
                                                        AppHelper::STATUS_RESOLVED => __(AppHelper::STATUS[AppHelper::STATUS_RESOLVED]),
                                                        AppHelper::STATUS_CLOSED => __(AppHelper::STATUS[AppHelper::STATUS_CLOSED]),
                                                    ],
                                                    old('status', $ticket->status_id ?? null),
                                                    [
                                                        'class' => 'form-control select2',
                                                        'required' => 'true',
                                                        'id' => 'status_id',
                                                        'name' => 'status_id',
                                                    ],
                                                ) !!}
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-xl-6 {{ !$isAdmin ? 'd-none' : '' }}">
                                            <div class="form-group has-feedback">
                                                <label for="priority_id"> {{ __('Priority') }} <span
                                                        class="text-danger">*</span></label>
                                                {!! Form::select(
                                                    'priority_id',
                                                    [
                                                        AppHelper::PRIORITY_LOW => __(AppHelper::PRIORITY[AppHelper::PRIORITY_LOW]),
                                                        AppHelper::PRIORITY_MEDIUM => __(AppHelper::PRIORITY[AppHelper::PRIORITY_MEDIUM]),
                                                        AppHelper::PRIORITY_HIGH => __(AppHelper::PRIORITY[AppHelper::PRIORITY_HIGH]),
                                                        AppHelper::PRIORITY_URGENT => __(AppHelper::PRIORITY[AppHelper::PRIORITY_URGENT]),
                                                    ],
                                                    old('priority', $ticket->priority_id ?? null),
                                                    [
                                                        'class' => 'form-control select2',
                                                        'required' => 'true',
                                                        'id' => 'priority_id',
                                                        'name' => 'priority_id',
                                                    ],
                                                ) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-xl-6">
                            <div class="form-group has-feedback">
                                <label for="subject"> {{ __('Subject') }} <span class="text-danger">*</span></label>
                                <input autofocus type="text" class="form-control" name="subject"
                                    placeholder="subject"
                                    value="@if ($ticket) {{ $ticket->subject }}@else{{ old('subject') }} @endif"
                                    required minlength="2" maxlength="255">
                                <span class="fa fa-info form-control-feedback"></span>
                                <span class="text-danger">{{ $errors->first('subject') }}</span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xl-6">
                            <div class="form-group has-feedback">
                                <label for="description"> {{ __('Description') }} <span
                                        class="text-danger">*</span></label>
                                <textarea name="description" class="form-control" placeholder="" rows="1" maxlength="500" required>
@if ($ticket)
{{ old('description') ?? $ticket->description }}@else{{ old('description') }}
@endif
</textarea>
                                <span class="fa fa-info form-control-feedback"></span>
                                <span class="text-danger">{{ $errors->first('description') }}</span>
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
        $(document).ready(function() {
            $('#photo-upload').on('click', function() {
                $("#photo").trigger("click")
            });
            $('#btn-remove-photo').on('click', function() {
                $("#photo").val('');
                $('#img-preview').val('');
                $("#photo-preview").removeAttr('src').addClass('d-none');
                $('#btn-remove-photo').addClass('d-none');
                $('#btn-upload-photo').removeClass('d-none');
                $('#student-photo').removeClass('opacity-25').addClass('opacity-100')
            })
            $("#photo").change(function(e) {
                var file = e.target.files[0];
                if (!file) {
                    return;
                }
                var reader = new FileReader();
                reader.onload = function(event) {
                    $("#photo-preview").attr("src", event.target.result);
                    $('#img-preview').val(event.target.result);
                    $("#photo-preview").removeClass("d-none");
                    $('#btn-upload-photo').addClass('d-none');
                    $('#btn-remove-photo').removeClass('d-none');
                    $('#student-photo').removeClass('opacity-100').addClass('opacity-25')
                };
                reader.readAsDataURL(file);
            });

            //hide show image preview
            if ($("#photo-preview").attr("src")) {
                $('#btn-upload-photo').addClass('d-none');
                $('#btn-remove-photo').removeClass('d-none');
            } else {
                $('#btn-upload-photo').removeClass('d-none');
                $('#btn-remove-photo').addClass('d-none');
                $("#photo-preview").addClass('d-none');
            }
        });
    </script>
@endsection
<!-- END PAGE JS-->
