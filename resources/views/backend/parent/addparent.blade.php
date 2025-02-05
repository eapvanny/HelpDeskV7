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
            border: 1px solid black;
            margin: 2px 2px;
            padding: .35em .625em .75em;
        }
        .radio {
            display: inline-block;
        }
    </style>
@endsection
<!-- BEGIN PAGE CONTENT-->
@section('pageContent')
    <!-- Section header -->
    <section class="content-header">
        <h1>
            {{ __('Parent') }}
            <small>@if($parent) Update @else {{ __('Add New') }} @endif</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{URL::route('user.dashboard')}}"><i class="fa fa-dashboard"></i> {{ __('Dashboard') }} </a></li>
            <li><a href="{{URL::route('parents.index')}}"> {{ __('Parent') }} </a></li>
            <li class="active">@if($parent) Update @else {{ __('Add') }} @endif</li>
        </ol>
    </section>
    <!-- ./Section header -->
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <form novalidate id="entryForm" action="@if($parent) {{URL::Route('parents.update', $parent->id)}} @else {{URL::Route('parents.store')}} @endif" method="post" enctype="multipart/form-data" autocomplete="off">
                        <div class="box-body">
                        @csrf
                        @if($parent)  {{ method_field('PATCH') }} @endif
                        <!-- Organization -->
                        <input type="hidden" name="org_id" value="{{ auth()->user()->org_id }}" />
                        <!-- End organization -->

                            <fieldset class="mather_block" >
                                <legend>@if($parent) {!! $parent->type == 'father' ? 'Father Info.:' : 'Mother Info.:' !!}@endif</legend>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-6">
                                            <div class="form-group has-feedback">
                                                <label for="family_name"> {{ __('Family Name') }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="family_name" placeholder="Family Name" required value="@if($parent){{ old('family_name')??$parent->family_name }}@else{{old('family_name')}}@endif"  maxlength="255">
                                                <span class="fa fa-info form-control-feedback"></span>
                                                <span class="text-danger">{{ $errors->first('family_name') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <label for="name"> {{ __('Given Name') }} <span class="text-danger">*</span></label>
                                            <input  type="text" class="form-control" name="name" placeholder="{{ __('Given Name') }}" required value="@if($parent){{ old('name')??$parent->name }}@else{{old('name')}}@endif" maxlength="255">
                                            <span class="fa fa-info form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label for="faces"> {{ __('Type') }} <span class="text-danger">*</span></label>
                                            {!! Form::select('consent_to_user_child_images', ['Not Consent','Consent'], $parent ? $parent->consent_to_user_child_images : null , ['placeholder' => 'Please Choose...','class' => 'form-control select2', 'required' => 'true']) !!}
                                            <span class="text-danger">{{ $errors->first('consent_to_user_child_images') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label for="father_name"> {{ __('City') }} </label>
                                            <input type="text" class="form-control" name="city" placeholder="City" value="@if($parent){{ $parent->city }}@else{{old('city')}}@endif"  maxlength="255">
                                            <span class="fa fa-info form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('city') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label for="father_name"> {{ __('Country') }} </label>
                                            <input type="text" class="form-control" name="country" placeholder="Country" value="@if($parent){{ $parent->country }}@else{{old('country')}}@endif"  maxlength="255">
                                            <span class="fa fa-info form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('country') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label for="phone_no"> {{ __('Phone/Mobile No.') }} <span class="text-danger">*</span></label>
                                            <input  type="text" class="form-control" name="phone_no"  placeholder="phone or mobile number" required value="@if($parent){{old('phone_no')??$parent->phone_no}}@else{{old('phone_no')}}@endif"  maxlength="15">
                                            <span class="fa fa-phone form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('phone_no') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label for="email"> {{ __('Email') }} <span class="text-danger"></span></label>
                                            <input  type="email" class="form-control" name="email"  placeholder="Email" value="@if($parent){{$parent->email}}@else{{old('email')}}@endif" maxlength="100">
                                            <span class="fa fa-envelope form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label for="father_phone_no"> {{ __('Passports No') }} </label>
                                            <input  type="text" class="form-control" name="passport" placeholder="Passports No" value="@if($parent){{$parent->passport}}@else{{old('passport')}}@endif" maxlength="15">
                                            <span class="fa fa-info form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('passport') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label for="id_card">@if($parent) {!! $parent->type == 'father' ? 'Father ID Card' : 'Mother ID Card' !!}@endif<span class="text-danger">*</span></label>
                                            <input  type="text" class="form-control" name="id_card"  placeholder="id card number" required value="@if($parent){{old('id_card')??$parent->id_card}}@else{{old('id_card')}}@endif"  minlength="1" maxlength="30"  autocomplete="new-password">
                                            <span class="fa fa-id-card form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('id_card') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label for="nationality"> {{ __('Nationality') }} <span class="text-danger"></span></label>
                                            <input  type="text" class="form-control" name="nationality"  placeholder="Nationality"  value="@if($parent){{$parent->nationality}}@else{{old('nationality')}}@endif" maxlength="100">
                                            <span class="fa fa-info form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('nationality') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label for="father_phone_no"> {{ __('Occupation') }} </label>
                                            <input  type="text" class="form-control" name="occupation" placeholder="Occupation" value="@if($parent){{$parent->occupation}}@else{{old('occupation')}}@endif" maxlength="15">
                                            <span class="fa fa-info form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('occupation') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label for="father_email"> {{ __('Name of the Company/Organization') }} <span class="text-danger"></span></label>
                                            <input  type="company" class="form-control" name="company"  placeholder="Name of the Company/Organization"  value="@if($parent){{$parent->company}}@else{{old('company')}}@endif" maxlength="100">
                                            <span class="fa fa-info form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('company') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label for="father_email"> {{ __("Company/Organization’s Address: City") }} <span class="text-danger"></span></label>
                                            <input  type="father_nationality" class="form-control" name="company_city"  placeholder="Company/Organization’s Address: City" value="@if($parent){{$parent->company_city}}@else{{old('company_city')}}@endif" maxlength="100">
                                            <span class="fa fa-info form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('company_city') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label for="father_email"> {{ __("Company/Organization’s Address: Country") }} <span class="text-danger"></span></label>
                                            <input  type="father_nationality" class="form-control" name="company_country"  placeholder="Company/Organization’s Address: Country" value="@if($parent){{$parent->company_country}}@else{{old('company_country')}}@endif" maxlength="100">
                                            <span class="fa fa-info form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('company_country') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group has-feedback">
                                            <label for="photo">@if($parent) {!! $parent->type == 'father' ? 'Father Profile Photo' : 'Mother Profile Photo' !!}@endif <span class="text-danger">[files: jpeg, jpg, png min:150x150 max-size: 2Mb]</span></label>
                                            <input  type="file" class="form-control" accept=".jpeg, .jpg, .png" name="profile_photo" placeholder="Photo image">
                                            @if($parent && isset($parent->profile_photo) && !empty($parent->profile_photo))
                                                <input type="hidden" name="oldprofile_photo" value="{{$parent->profile_photo}}">
                                            @endif
                                            <span class="glyphicon glyphicon-open-file form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('profile_photo') }}</span>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <label for="address"> {{ __('Permanent Address') }} <span class="text-danger">*</span></label>
                                            <textarea name="address" class="form-control" maxlength="500" >@if($parent){{ $parent->address }}@else{{ old('address') }} @endif</textarea>
                                            <span class="fa fa-location-arrow form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('address') }}</span>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <label for="faces"> {{ __("CONSENT TO USE CHILD’S IMAGES") }} <span class="text-danger">*</span></label>
                                            {!! Form::select('consent_to_user_child_images', ['Not Consent','Consent'], $parent ? $parent->consent_to_user_child_images : null , ['placeholder' => 'Please Choose...','class' => 'form-control select2', 'required' => 'true']) !!}
                                            <span class="text-danger">{{ $errors->first('consent_to_user_child_images') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group has-feedback">
                                            <label for="faces">@if($parent) {!! $parent->type == 'father' ? 'Father Photo' : 'Mother Photo' !!}@endif <span class="text-danger"></span></label>
                                            <div id=@if($parent) {!! $parent->type == 'father' ? 'fatherFaces' : 'motherFaces' !!}@endif class="dropzone">
                                                <div class="dz-message" data-dz-message><i class="fa fa-upload" aria-hidden="true"></i> {{ __('Drop image here') }} <br><span class="text-danger">[files: jpeg, jpg, png min: 150x150 max-size: 200Kb]</span></div>
                                                <div class="dz-default dz-message"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    @if(!$parent)
                                        <div class="col-md-3">
                                            <div class="form-group has-feedback">
                                                <label for="username"> {{ __('Username') }} <span class="text-danger"></span></label>
                                                <input  type="text" class="form-control" value="{{ old('username') }}" name="username" minlength="5" maxlength="255">
                                                <span class="glyphicon glyphicon-info-sign form-control-feedback"></span>
                                                <span class="text-danger">{{ $errors->first('username') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group has-feedback">
                                                <label for="password"> {{ __('Password') }} <span class="text-danger"></span></label>
                                                <input type="password" class="form-control" name="password"  value="{{ old('password') }}" placeholder="Password" minlength="6" maxlength="50">
                                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                            </div>
                                        </div>

                                    @else

                                        @if (!$parent->user_id)
                                            <div class="col-md-3">
                                                <div class="form-group has-feedback">
                                                    <label for="username"> {{ __('Username') }} <span class="text-danger"></span></label>
                                                    <input  type="text" class="form-control" value="{{old('username')}}" name="username" required minlength="5" maxlength="255">
                                                    <span class="glyphicon glyphicon-info-sign form-control-feedback"></span>
                                                    <span class="text-danger">{{ $errors->first('username') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group has-feedback">
                                                    <label for="password"> {{ __('Password') }} <span class="text-danger"></span></label>
                                                    <input type="password" class="form-control" name="password" placeholder="Password" minlength="6" maxlength="50">
                                                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                                </div>
                                            </div>
                                        @endif

                                    @endif
                                </div>
                            </fieldset>

                            <input type="hidden" id="f_temp_path" name="f_temp_path" value="f_parent_{{time()}}">
                            <input type="hidden" id="m_temp_path" name="m_temp_path" value="m_parent_{{time()}}">
                            <input type="hidden" id="is_delete" name="is_delete" value="1">
                            <hr>

                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <a href="{{URL::route('parents.index')}}" class="btn btn-default"> {{ __('Cancel') }} </a>
                            <button type="submit" class="btn btn-info pull-right text-white"><i class="fa @if($parent) fa-refresh @else fa-plus-circle @endif"></i> @if($parent) Update @else Add @endif</button>
                        </div>
                    </form>
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
            $('button[type=submit]').on('click',function(e) {
                e.preventDefault();
                // $('#is_delete').val('0');
                isDelete = 0;
                $('#entryForm').submit();
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
                }

            });

        });

        Dropzone.options.fatherFaces =
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
                success: function(file, response)
                {
                    console.log(response);
                },
                error: function(file, response)
                {
                    return false;
                },

                init: function () {
                    var imgUrl = "{{$parent['urlFather']}}";
                    var imgSize = "{{$parent['urlFatherSize']}}";
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
                        formData.append("temp_path", $("#f_temp_path").val());
                    });
                    this.on("removedfile", function (file) {
                        $.post({
                            url: "{{route('face.delete.tempfile')}}",
                            data: {filename: file.name,temp_path:$("#f_temp_path").val(), _token: $('meta[name="csrf-token"]').attr('content')},
                            dataType: 'json',
                            success: function (data) {

                            }
                        });
                    });
                }
            };
        Dropzone.options.motherFaces =
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
                    var imgUrl = "{{$parent['urlMother']}}";
                    var imgSize = "{{$parent['urlMotherSize']}}";
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
                        formData.append("temp_path", $("#m_temp_path").val());
                    });
                    this.on("removedfile", function (file) {
                        $.post({
                            url: "{{route('face.delete.tempfile')}}",
                            data: {filename: file.name,temp_path:$("#m_temp_path").val(), _token: $('meta[name="csrf-token"]').attr('content')},
                            dataType: 'json',
                            success: function (data) {

                            }
                        });
                    });
                }
            };
    </script>


@endsection
<!-- END PAGE JS-->
