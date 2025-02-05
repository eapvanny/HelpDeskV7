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

        <ol class="breadcrumb">
            <li><a href="{{URL::route('user.dashboard')}}"><i class="fa fa-dashboard"></i> {{ __('Dashboard') }} </a></li>
            <li><{{ __('Student Management') }}</li>
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
                        <small>@if($parent) {{ __('Update') }} @else {{ __('Add New') }} @endif</small>
                    </h1>
                </div>
                <div class="wrap-outter-box">
                <div class="box box-info">
                    <form novalidate id="entryForm" action="@if($parent) {{URL::Route('parents.update', $parent->id)}} @else {{URL::Route('parents.store')}} @endif" method="post" enctype="multipart/form-data" autocomplete="off">
                        <div class="box-body">
                        @csrf
                        @if($parent)  {{ method_field('PATCH') }} @endif
                        <!-- Organization -->
                        @auth
                            {{-- @if(auth()->user()->newRole->role_id === AppHelper::USER_SUPER_ADMIN) --}}
                            {!! AppHelper::selectOrg($errors, $parent->org_id ?? 0,$role_ref_id) !!}
                            {{-- @endif --}}
                        @endauth
                        <!-- End organization -->
                            @include('backend.page.parents.create')
                            <hr>
                            <div class="row" hidden>
                                @if(!$parent)
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label for="username"> {{ __('Username') }} <span class="text-danger">*</span></label>
                                            <input  type="text" class="form-control" value="{{ old('username') }}" name="username" required minlength="5" maxlength="255" autocomplete="new-password">
                                            <span class="glyphicon glyphicon-info-sign form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('username') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label for="password"> {{ __('Password') }} <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="password"  value="{{ old('password') }}" placeholder="{{ __('Password') }}" required minlength="6" maxlength="50" autocomplete="new-password">
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('password') }}</span>
                                        </div>
                                    </div>

                                @else

                                    @if (!$parent->user_id)
                                        <div class="col-md-4">
                                            <div class="form-group has-feedback">
                                                <label for="username"> {{ __('Username') }} <span class="text-danger">*</span></label>
                                                <input  type="text" class="form-control" value="{{old('username')}}" name="username" required minlength="5" maxlength="255" autocomplete="new-password">
                                                <span class="glyphicon glyphicon-info-sign form-control-feedback"></span>
                                                <span class="text-danger">{{ $errors->first('username') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group has-feedback">
                                                <label for="password"> {{ __('Password') }} <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control" name="password" placeholder="{{ __('Password') }}" required minlength="6" maxlength="50" autocomplete="new-password">
                                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                            </div>
                                        </div>
                                    @endif

                                @endif
                                {{-- @if($parent && !$parent->user_id)
                                    <div class="col-md-4">
                                        <div class="form-group has-feedback">
                                            <label for="user_id">User
                                                <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Leave as it is, if not need user"></i>
                                            </label>
                                            {!! Form::select('user_id', $users, null , ['placeholder' => 'Pick if needed','class' => 'form-control select2']) !!}
                                            <span class="form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('user_id') }}</span>
                                        </div>
                                    </div>

                                @endif --}}
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <a href="{{URL::route('parents.index')}}" class="btn btn-default"> {{ __('Cancel') }} </a>
                            <button type="submit" class="btn btn-info pull-right text-white"><i class="fa @if($parent) fa-refresh @else fa-plus-circle @endif"></i> @if($parent) Update @else {{ __('Add    ') }} @endif</button>

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
