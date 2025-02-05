<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle') Parent @endsection
<!-- End block -->

<!-- Page body extra class -->
@section('bodyCssClass') @endsection
<!-- End block -->
@section('extraStyle')
    <link rel="stylesheet" href="{{asset('portal/css/dropzone.css')}}">
    <link href="{{asset('portal/css/cropper.css')}}" rel="stylesheet"/>
    <style>
        .dropzone .dz-preview .dz-image img {
            width: 120px;
            height: 120px;
        }
        .dropzone .dz-preview .dz-remove{
            display: none;
        }
        fieldset .form-group{
            margin-bottom: 0px;
        }
        .list-radio .form-group.has-error .help-block {
            position: absolute;
            width: 300px;
            bottom: -18px;
        }
        .list-time-schedule .error.help-block{
            position: absolute;
            width: 300px;
            bottom: -18px;
            color: #dd4b39;
            font-size: 12px;
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
                border: 1px solid #ddd;
                margin: 10px 0;
                padding: 0.35em 0.625em 0.75em;
                border-radius: 10px;
        }

        fieldset .form-group{
            margin-bottom: 0px;
        }
        .list-radio .form-group.has-error .help-block {
            position: absolute;
            width: 300px;
            bottom: -18px;
        }
        .list-time-schedule .error.help-block{
            position: absolute;
            width: 300px;
            bottom: -18px;
            color: #dd4b39;
            font-size: 12px;
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
        fieldset {
                padding: 1em 0.625em 1em;
                border: 1px solid #ddd;
                margin: 10px 0;
                padding: 0.35em 0.625em 0.75em;
                border-radius: 10px;
        }
        fieldset > #student-photo{
			overflow: hidden;
			cursor: pointer;
			width: 100%;
			height: 331px;
			background-color: #f5f5f5;
		}
		fieldset > #student-photo > #btn-upload-photo{
			min-width: 100px;
			min-height: 100px;
			background-color: #ddd;
			font-size: 25px;
		}

        fieldset > #photo-preview{
			height: 250px;
			width: 250px;
			position: absolute;
			object-fit: cover;
		}

    .fly_action_btn{
        z-index: 2;
    }
    </style>
@endsection
<!-- BEGIN PAGE CONTENT-->
@section('pageContent')
    <!-- Section header -->
    <section class="content-header">

        <ol class="breadcrumb">
            <li><a href="{{URL::route('user.dashboard')}}"><i class="fa fa-dashboard"></i> {{ __('Dashboard') }} </a></li>
            <li>{{ __('Student Management') }}</li>
            <li><a href="{{URL::route('parents.index')}}"> {{ __('Parents') }} </a></li>
            <li class="active">@if($parent) Update @else {{ __('Add') }} @endif</li>
        </ol>
    </section>
    <!-- ./Section header -->
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="wrap-outter-header-title">
                    <h1>
                        {{ __('Parent') }}
                        <small>@if($parent) Update @else {{ __('Add New') }} @endif</small>
                    </h1>

                    <div class="action-btn-top none_fly_action_btn">
                        <a href="{{URL::route('parents.index')}}" class="btn btn-default"> {{ __('Cancel') }} </a>
                        <button type="submit" class="submitClick btn btn-info pull-right text-white"><i class="fa @if($parent) fa-refresh @else fa-plus-circle @endif"></i> @if($parent) Update @else {{ __('Add') }} @endif</button>
                        @if(!$parent)
                        <button type="submit" class="submitClick submitAndContinue btn btn-success text-white">
                            <i class="fa fa-plus-circle"></i> {{ __('Save & Add New') }}
                        </button>
                        @endif
                    </div>
                </div>
                <div class="wrap-outter-box">
                <div class="box box-info">
                    <form novalidate id="entryForm" action="@if($parent) {{URL::Route('parents.update', $parent->id)}} @else {{URL::Route('parents.store')}} @endif" method="post" enctype="multipart/form-data" autocomplete="off">
                        <div class="box-body">
                        @csrf
                        @if($parent)  {{ method_field('PATCH') }} @endif
                        <!-- Organization -->
                        <input type="hidden" name="org_id" value="{{ auth()->user()->org_id }}" />
                        <!-- End organization -->
                            <div class="row">
                                <div class="row-span-6 col-6 mt-4">
                                    <div class="form-group has-feedback position-relative">
                                        <input type="file" id="photo" name="profile_photo" style="display: none" accept="image/*">
                                        <button type="button" class="btn btn-light text-secondary fs-5 position-absolute d-none m-2 end-0 z-1" id="btn-remove-photo"><i class="fa-solid fa-trash"></i></button>
                                        <fieldset id="photo-upload" class="p-0 d-flex align-items-center justify-content-center z-0 position-relative">
                                            <img class="rounded mx-auto d-block @if(!old('oldprofile_photo') && !old('img-preview') && !isset($parent)){{'d-none'}}@endif z-1" id="photo-preview" name="oldprofile_photo" src="@if(optional($parent)->profile_photo){{Storage::url($parent->profile_photo)}}@else{{old('oldprofile_photo')}}@endif" alt="photo">
                                            {{-- <input type="hidden" id="img-preview" name="oldprofile_photo" value="{{ $parent->profile_photo }}"> --}}
                                            @if($parent && isset($parent->profile_photo))
                                                <input type="hidden" id="img-preview" name="oldprofile_photo" value="{{$parent->profile_photo}}">
                                             @endif
                                            <div class="d-flex align-items-center justify-content-center bg-transparent z-2  @if(!old('img-preview')){{'opacity-100'}} @else {{'opacity-25'}}@endif" id="student-photo">
                                                <button class="btn p-3 rounded-circle" id="btn-upload-photo" type="button" onclick="" >
                                                    <i class="fa-solid fa-camera-retro"></i>
                                                </button>
                                            </div>
                                            <label class="position-absolute bottom-0 text-center w-100 mb-2" for="photo">
                                                {{__('Parent photos only accept jpg, png, jpeg images')}}
                                            </label>
                                        </fieldset>
                                    </div>
                                </div>
                               <div class="col-md-6">
                                    <div class="col-md-12">
                                        <div class="form-group has-feedback">
                                            <label for="faces"> {{ __('Type') }} <span class="text-danger">*</span></label>
                                            <select class="form-control select2" required="true" name="type" {{$parent ? 'disabled':''}}>
                                                <option value="">{{__('Please Choose')}}...</option>
                                                <option value="father" @if(old('type')) @if(old('type')=='father'){{ 'selected' }} @endif @elseif(isset($parent->type)) @if($parent->type=='father'){{ 'selected' }}@endif @endif>{{__('Father')}}</option>
                                                <option value="mother" @if(old('type')) @if(old('type')=='mother'){{ 'selected' }} @endif @elseif(isset($parent->type)) @if($parent->type=='mother'){{ 'selected' }}@endif @endif>{{__('Mother')}}</option>
                                                <option value="guardian" @if(old('type')) @if(old('type')=='guardian'){{ 'selected' }} @endif @elseif(isset($parent->type)) @if($parent->type=='guardian'){{ 'selected' }}@endif @endif>{{__('Guardian')}}</option>
                                            </select>
                                            <span class="form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('gender') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group has-feedback">
                                            <label for="id_card"> {{ __('ID Card') }} <span class="text-danger">*</span></label>
                                            {{-- <label for="id_card">@if($parent) {!! $parent->type == 'father' ? 'Father ID Card' : 'Mother ID Card' !!}@endif<span class="text-danger">*</span></label> --}}
                                            <input  type="text" class="form-control" name="id_card"  placeholder="{{ __('id card number') }}" required value="@if($parent){{old('id_card')??$parent->id_card}}@else{{old('id_card')}}@endif"  minlength="9" maxlength="255"  autocomplete="new-password">
                                            <span class="fa fa-id-card form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('id_card') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group has-feedback">
                                            <label for="phone_no"> {{ __('Phone/Mobile No.') }} <span class="text-danger">*</span></label>
                                            <input  type="number" class="form-control" name="phone_no"  placeholder="{{ __('phone or mobile number') }}" required value="@if($parent){{old('phone_no')??$parent->phone_no}}@else{{old('phone_no')}}@endif"  maxlength="15">
                                            <span class="fa fa-phone form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('phone_no') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 d-none">
                                        <div class="form-group has-feedback">
                                            <label for="father_phone_no"> {{ __('Passports No') }} </label>
                                            <input  type="text" class="form-control" name="passport" placeholder="{{ __('Passports No') }}" value="@if($parent){{$parent->passport}}@else{{old('passport')}}@endif" maxlength="15">
                                            <span class="fa fa-info form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('passport') }}</span>
                                        </div>
                                    </div>
                               </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label for="family_name"> {{ __('Family Name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="family_name" placeholder="{{ __('Family Name') }}" required value="@if($parent){{ old('family_name')??$parent->family_name }}@else{{old('family_name')}}@endif"  maxlength="255">
                                        <span class="fa fa-info form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('family_name') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label for="name"> {{ __('Given Name') }} <span class="text-danger">*</span></label>
                                        <input  type="text" class="form-control" name="name" placeholder="{{ __('Given Name') }}" required value="@if($parent){{ old('name')??$parent->name }}@else{{old('name')}}@endif" maxlength="255">
                                        <span class="fa fa-info form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label for="family_name_latin"> {{ __('Family Name In Latin') }} <span class="text-danger">*</span></label>
                                        <input  type="text" class="form-control" name="family_name_latin" placeholder="{{ __('Family Name Latin') }}" required value="@if($parent){{ old('family_name_latin')??$parent->family_name_latin }}@else{{old('family_name_latin')}}@endif" maxlength="255">
                                        <span class="fa fa-info form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('family_name_latin') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label for="name_in_latin"> {{ __('Name In Latin') }} <span class="text-danger">*</span></label>
                                        <input  type="text" class="form-control" name="name_in_latin" placeholder="{{ __('Name In Latin') }}" required value="@if($parent){{ old('name_in_latin')??$parent->name_in_latin }}@else{{old('name_in_latin')}}@endif" maxlength="255">
                                        <span class="fa fa-info form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('name_in_latin') }}</span>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4 d-none">
                                    <div class="form-group has-feedback">
                                        <label for="father_name"> {{ __('City') }} </label>
                                        <input type="text" class="form-control" name="city" placeholder="{{ __('City') }}" value="@if($parent){{ $parent->city }}@else{{old('city')}}@endif"  maxlength="255">
                                        <span class="fa fa-info form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('city') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4 d-none">
                                    <div class="form-group has-feedback">
                                        <label for="father_name"> {{ __('Country') }} </label>
                                        <input type="text" class="form-control" name="country" placeholder="{{ __('Country') }}" value="@if($parent){{ $parent->country }}@else{{old('country')}}@endif"  maxlength="255">
                                        <span class="fa fa-info form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('country') }}</span>
                                    </div>
                                </div>

                            </div>
                            <div class="row d-none">
                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="father_email"> {{ __('Name of the Company/Organization') }} <span class="text-danger"></span></label>
                                        <input  type="company" class="form-control" name="company"  placeholder="{{ __('Name of the Company/Organization') }}"  value="@if($parent){{$parent->company}}@else{{old('company')}}@endif" maxlength="100">
                                        <span class="fa fa-info form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('company') }}</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="father_email"> {{ __("Company/Organization’s Address: Country") }} <span class="text-danger"></span></label>
                                        <input  type="father_nationality" class="form-control" name="company_country"  placeholder="{{ __('Company/Organization’s Address: Country') }}" value="@if($parent){{$parent->company_country}}@else{{old('company_country')}}@endif" maxlength="100">
                                        <span class="fa fa-info form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('company_country') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-3">
                                    <div class="form-group has-feedback">
                                        <label for="nationality"> {{ __('Nationality') }} <span class="text-danger"></span></label>
                                        <input  type="text" class="form-control" name="nationality"  placeholder="{{ __('Nationality') }}"  value="@if($parent){{$parent->nationality}}@else{{old('nationality')}}@endif" maxlength="100">
                                        <span class="fa fa-info form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('nationality') }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="form-group has-feedback">
                                        <label for="father_phone_no"> {{ __('Occupation') }} </label>
                                        <input  type="text" class="form-control" name="occupation" placeholder="{{ __('Occupation') }}" value="@if($parent){{$parent->occupation}}@else{{old('occupation')}}@endif" maxlength="15">
                                        <span class="fa fa-info form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('occupation') }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group has-feedback">
                                        <label for="father_email"> {{ __('Occupation Address') }} <span class="text-danger"></span></label>
                                        <input  type="father_nationality" class="form-control" name="company_city"  placeholder="{{ __('Occupation Address') }}" value="@if($parent){{$parent->company_city}}@else{{old('company_city')}}@endif" maxlength="100">
                                        <span class="fa fa-info form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('company_city') }}</span>
                                    </div>
                                </div>
                                <div class="colo-12 col-md-6 d-none">
                                    <div class="form-group has-feedback">
                                        <div class="form-group has-feedback">
                                            <label for="faces"> {{ __("CONSENT TO USE CHILD’S IMAGES") }} <span class="text-danger">*</span></label>
                                            {!! Form::select('consent_to_user_child_images', ['Not Consent','Consent'], $parent ? $parent->consent_to_user_child_images : null , ['placeholder' => 'Please Choose...','class' => 'form-control select2', 'required' => 'true']) !!}
                                            <span class="text-danger">{{ $errors->first('consent_to_user_child_images') }}</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="colo-12 col-md-12">
                                    <div class="form-group has-feedback">
                                        <label for="address"> {{ __('Permanent Address') }} </label>
                                        <textarea name="address" class="form-control" maxlength="500" >@if($parent){{ $parent->address }}@else{{ old('address') }} @endif</textarea>
                                        <span class="fa fa-location-arrow form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('address') }}</span>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group has-feedback">
                                        <label for="photo"> {{ __('Profile Photo') }} <span class="text-danger">[files: jpeg, jpg, png min:150x150 max-size: 2Mb]</span></label>
                                        <input  type="file" class="form-control" accept=".jpeg, .jpg, .png" name="profile_photo" placeholder="{{ __('Photo image') }}">
                                        @if($parent && isset($parent->profile_photo))
                                            <input type="hidden" name="oldprofile_photo" value="{{$parent->profile_photo}}">
                                        @endif
                                        <span class="glyphicon glyphicon-open-file form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('profile_photo') }}</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2">
                                    <div class="form-group has-feedback">
                                        @if($parent && isset($parent->profile_photo))
                                        <img class="img-responsive img-circle edit-img" src="@if($parent->photo){{ asset('storage/parents/')}}/{{ $parent->profile_photo }} @else {{ asset('images/avatar.png')}} @endif">
                                        @endif

                                    </div>
                                </div>
                            </div> --}}

                            <hr>
                            @if (!$parent || !$parent->email)
                                <div class="box-header d-none">
                                    <input type="checkbox" id="parent_access" name="parent_access">
                                    <h3 class="box-title"> {{ __('Parent Access') }} </h3>
                                </div>
                                <fieldset id="form_parent_access" class="form_parent_access d-none">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group has-feedback">
                                                <label for="email"> {{ __('Email') }} <span class="text-danger">*</span></label>
                                                <input id="txt-email" type="text" class="form-control" required value="@if($parent && $parent->user){{ old('email') ?? $parent->user->email }}@else{{ old('email') }}@endif" name="email" placeholder="{{ __('email address') }}" minlength="5" maxlength="255" autocomplete="new-password">
                                                <span class="glyphicon glyphicon-info-sign form-control-feedback"></span>
                                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                            </div>

                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group has-feedback">
                                                <label for="username"> {{ __('Username') }} <span class="text-danger">*</span></label>
                                                <input id="txt-username" type="text" class="form-control" required value="@if($parent && $parent->user){{ old('username') ?? $parent->user->username }}@else{{ old('username') }}@endif" name="username" placeholder="{{ __('username') }}" minlength="5" maxlength="255" autocomplete="new-password">
                                                <span class="glyphicon glyphicon-info-sign form-control-feedback"></span>
                                                <span class="text-danger">{{ $errors->first('username') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group has-feedback">
                                                <label for="password"> {{ __('Password') }} <span class="text-danger">*</span></label>
                                                <input id="txt-pass" type="password" class="form-control" name="password" required value="{{ old('password') }}" placeholder="{{ __('password') }}" minlength="6" maxlength="50" autocomplete="new-password">
                                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            @endif
                                {{--
                            <input type="hidden" id="f_temp_path" name="f_temp_path" value="f_parent_{{time()}}">
                            <input type="hidden" id="m_temp_path" name="m_temp_path" value="m_parent_{{time()}}"> --}}
                            <input type="hidden" id="par_temp_path" name="par_temp_path" value="par_parent_{{time()}}">
                            <input type="hidden" id="is_delete" name="is_delete" value="1">


                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer mt-md-3">

                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
<!-- END PAGE CONTENT-->

<!-- BEGIN PAGE JS-->
@section('extraScript')
    <script src="{{ asset('/backend/js/parent.js') }}"></script>
    <script src="{{asset('portal/js/dropzone.js')}}"></script>
    <script src="{{asset('portal/js/cropper.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            Generic.initCommonPageJS();
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let isDelete = 1;

            @if ( old('parent_access') )
                $( "#parent_access" ).prop('checked', true);
                $('#form_parent_access').removeClass('d-none');
            @endif

            $( "#parent_access" ).on('ifChanged', function(event) {
                if(this.checked) {
                    $('#form_parent_access').removeClass('d-none');
                } else {
                    $('#form_parent_access').addClass('d-none');
                }
            });


            $(".submitClick").on('click', function(){
                event.preventDefault();
                if ($(this).hasClass('submitAndContinue')) {
                    $(".box-footer").append('<input type="hidden" name="saveandcontinue" value="1" />');
                }else {
                    $("input[name='saveandcontinue']").each(function(){
                        $(this).remove();
                    });
                }
                // Check if the checkbox is checked
                if ($("#parent_access").is(":checked")) {
                    // If checked, submit the form with the data
                    $("#entryForm").submit();
                } else {
                    // If not checked, clear the values and then submit the form
                    $('#txt-email').val('');
                    $('#txt-username').val('');
                    $('#txt-pass').val('');
                    $("#entryForm").submit();
                }
            });

            Dropzone.autoDiscover = false;
            $(window).on('beforeunload', function(){
                if(isDelete==1){
                    $.ajax({
                        type: "POST",
                        url: "{{route('face.delete.temp')}}",
                        data: {'temp_path' : $("#f_temp_path").val() },
                        dataType: 'json',
                        async: false,
                        success: function (response) {
                            if( response.status === true ) {
                                alert('File Deleted!');
                            }
                            else alert('Something Went Wrong!');
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: "{{route('face.delete.temp')}}",
                        data: {'temp_path' : $("#m_temp_path").val() },
                        dataType: 'json',
                        async: false,
                        success: function (response) {
                            if( response.status === true ) {
                                alert('File Deleted!');
                            }
                            else alert('Something Went Wrong!');
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: "{{route('face.delete.temp')}}",
                        data: {'temp_path' : $("#par_temp_path").val() },
                        dataType: 'json',
                        async: false,
                        success: function (response) {
                            if( response.status === true ) {
                                alert('File Deleted!');
                            }
                            else alert('Something Went Wrong!');
                        }
                    });
                }

            });

        });

        Dropzone.options.Faces =
            {

                url: "{{route('face.upload')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                maxFiles: 1,
                resizeWidth: 500,
                maxFilesize: 1024,
                acceptedFiles: ".jpeg,.jpg,.png",
                addRemoveLinks: true,
                timeout: 50000,
                success: function(file, response)
                {
                    console.log(response);
                },
                error: function(file, response)
                {
                    return false;
                },
                transformFile: function(file, done) {
                    // Create Dropzone reference for use in confirm button click handler
                    var myDropZone = this;
                    // Create the image editor overlay
                    var editor = document.createElement('div');
                    editor.style.position = 'fixed';
                    editor.style.left = 0;
                    editor.style.right = 0;
                    editor.style.top = 0;
                    editor.style.bottom = 0;
                    editor.style.zIndex = 9999;
                    editor.style.backgroundColor = '#000';
                    document.body.appendChild(editor);
                    // Create confirm button at the top left of the viewport
                    var buttonConfirm = document.createElement('button');
                    buttonConfirm.style.position = 'absolute';
                    buttonConfirm.style.left = '10px';
                    buttonConfirm.style.top = '10px';
                    buttonConfirm.style.zIndex = 9999;
                    buttonConfirm.textContent = 'Confirm';
                    editor.appendChild(buttonConfirm);
                    buttonConfirm.addEventListener('click', function() {
                        // Get the canvas with image data from Cropper.js
                        var canvas = cropper.getCroppedCanvas({
                            width: {{getSchoolConfig('dropzone_crop_width')}},
                            height: {{getSchoolConfig('dropzone_crop_height')}}
                        });
                        // Turn the canvas into a Blob (file object without a name)
                        canvas.toBlob(function(blob) {
                            // Create a new Dropzone file thumbnail
                            myDropZone.createThumbnail(
                                blob,
                                myDropZone.options.thumbnailWidth,
                                myDropZone.options.thumbnailHeight,
                                myDropZone.options.thumbnailMethod,
                                false,
                                function(dataURL) {

                                    // Update the Dropzone file thumbnail
                                    myDropZone.emit('thumbnail', file, dataURL);
                                    // Return the file to Dropzone
                                    done(blob);
                                });
                        });
                        // Remove the editor from the view
                        document.body.removeChild(editor);
                    });
                    // Create an image node for Cropper.js
                    var image = new Image();
                    image.src = URL.createObjectURL(file);
                    editor.appendChild(image);
                    // Create Cropper.js
                    var cropper = new Cropper(image, { aspectRatio: 1 });
                },
                init: function () {
                    var imgUrl = "{{$parent['urlFace'] ?? ''}}";
                    var imgSize = "{{$parent['urlFaceSize'] ?? ''}}";
                    var currentFile = null;

                    if (imgUrl!=''&&imgSize!=0){
                        var mockFile = { name: imgUrl,size:imgSize, type: 'image/jpeg' };
                        this.options.addedfile.call(this, mockFile);
                        this.options.thumbnail.call(this, mockFile,imgUrl);
                        mockFile.previewElement.classList.add('dz-success');
                        mockFile.previewElement.classList.add('dz-complete');
                        currentFile = mockFile;
                    }
                    this.on("addedfile", function(file){
                        if (currentFile) {
                            this.removeFile(currentFile);
                        }
                        currentFile = file;

                    });
                    this.on("maxfilesexceeded", function(file) {
                        this.removeAllFiles();
                        this.addFile(file);
                    });
                    this.on("complete", function (file) {
                        if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                            // alert('done');
                        }
                    });
                    this.on("sending", function(file, xhr, formData) {
                        formData.append("temp_path", $("#par_temp_path").val());
                    });
                    this.on("removedfile", function (file) {
                        $.post({
                            url: "{{route('face.delete.tempfile')}}",
                            data: {filename: file.name,temp_path:$("par_temp_path").val(), _token: $('meta[name="csrf-token"]').attr('content')},
                            dataType: 'json',
                            success: function (data) {

                            }
                        });
                    });
                }
            };

        // photo uploard when create
        $('#photo-upload').on('click', function(){
        $("#photo").trigger("click")
		});
		$('#btn-remove-photo').on('click', function(){
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
    </script>


@endsection
<!-- END PAGE JS-->
