<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle')
    Support
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
            height: 342px;
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
            <li><a href="{{ URL::route('contact.index') }}"> {{ __('Support') }} </a></li>
            <li class="active">
                @if ($contact)
                    {{ __('Update') }}
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
            action="@if ($contact) {{ URL::Route('contact.update', $contact->id) }} @else {{ URL::Route('contact.store') }} @endif"
            method="post" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
                <div class="col-md-12">
                    <div class="wrap-outter-header-title">
                        <h1>
                            {{ __('Support') }}
                            <small>
                                @if ($contact)
                                    {{ __('Update') }}
                                @else
                                    {{ __('Add New') }}
                                @endif
                            </small>
                        </h1>

                        <div class="box-tools pull-right">
                            <a href="{{ URL::route('contact.index') }}" class="btn btn-default">{{ __('Cancel') }}</a>
                            <button type="submit" class="btn btn-info pull-right text-white"><i
                                    class="fa @if ($contact) fa-refresh @else fa-plus-circle @endif"></i>
                                @if ($contact)
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
                <div class="box-body">
                    @csrf
                    @if ($contact)
                        @method('PUT')
                    @endif
                    <div class="row">
                        <div class="form-group has-feedback">
                            <div class="row">
                                <div class="row-span-6 col-sm-12 col-md-6 mt-4">
                                    <div class="form-group has-feedback position-relative">
                                        <input type="file" id="photo" name="photo" style="display: none" accept="image/*">
                                        <button type="button" class="btn btn-light text-secondary fs-5 position-absolute d-none m-2 end-0 z-1" id="btn-remove-photo"><i class="fa-solid fa-trash"></i></button>
                                        <fieldset id="photo-upload" class="p-0 d-flex align-items-center justify-content-center z-0 position-relative">
                                            <img class="rounded mx-auto d-block @if(!old('oldphoto') && !old('img-preview') && !isset($contact)){{'d-none'}}@endif z-1" id="photo-preview" name="oldphoto" src="@if(optional($contact)->photo){{asset('storage/' . $contact->photo)}}@else{{old('oldphoto')}}@endif" alt="photo">
                                            <input type="hidden" id="img-preview" name="oldphoto" value="@if(optional($contact)->photo){{asset($contact->photo)}}@endif">
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
                                        <div class="col-lg-6 col-md-6 col-xl-6">
                                            <div class="form-group has-feedback">
                                                <label for="department_id"> {{ __('Department') }} <span
                                                        class="text-danger">*</span>
                                                    <i class="fa fa-question-circle" data-toggle="tooltip"
                                                        data-placement="bottom" title="Select Department"></i>
                                                </label>
                                                {!! Form::select('department_id', $departments, old('department_id', optional($contact)->department_id), [
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
                                        <div class="col-md-6">
                                            <div class="form-group has-feedback">
                                                <label for="id_card"> {{ __('Staff ID') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="id_card"
                                                    placeholder="id_card"
                                                    value="@if ($contact) {{ $contact->id_card }}@else{{ old('id_card') }} @endif"
                                                    required minlength="3" maxlength="10">
                                                <span class="fa fa-info form-control-feedback"></span>
                                                <span class="text-danger">{{ $errors->first('id_card') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group has-feedback">
                                                <label for="phone_no"> {{ __('Phone No.') }}</label>
                                                <input type="text" class="form-control" name="phone_no"
                                                    placeholder="phone or mobile number"
                                                    value="@if ($contact) {{ $contact->phone_no }}@else{{ old('phone_no') }} @endif"
                                                    maxlength="15">
                                                <span class="fa fa-phone form-control-feedback"></span>
                                                <span class="text-danger">{{ $errors->first('phone_no') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group has-feedback">
                                                <label for="code">{{ __('Name') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control" placeholder=""
                                                    value="@if (old('name')) {{ old('name') }}@elseif($contact){{ $contact->name }} @endif"
                                                    minlength="1" maxlength="50" required />
                                                <span class="fa fa-info form-control-feedback"></span>
                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback">
                                                <label for="name_in_latin">{{ __('Name in Latin') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="name_in_latin" class="form-control"
                                                    placeholder=""
                                                    value="@if (old('name_in_latin')) {{ old('name_in_latin') }}@elseif($contact){{ $contact->name_in_latin }} @endif"
                                                    minlength="1" maxlength="50" required />
                                                <span class="fa fa-info form-control-feedback"></span>
                                                <span class="text-danger">{{ $errors->first('name_in_latin') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback">
                                                <label for="link_telegram"> {{ __('Link Telegram') }} <span
                                                        class="text-danger">*</span></label>
                                                <input autofocus type="text" class="form-control" name="link_telegram"
                                                    placeholder="link_telegram"
                                                    value="@if ($contact) {{ $contact->link_telegram }}@else{{ old('link_telegram') }} @endif"
                                                    required minlength="2" maxlength="255">
                                                <span class="fa fa-info form-control-feedback"></span>
                                                <span class="text-danger">{{ $errors->first('link_telegram') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
