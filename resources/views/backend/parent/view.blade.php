<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle') Parent Profile @endsection
<!-- End block -->

<!-- Page body extra css -->
@section('extraStyle')
    <link href="{{asset('portal/css/cropper.css')}}" rel="stylesheet"/>
    <style>
        .box-header {
            padding: 20px;
        }
        .form-check-input{
            margin: 0;
            width: 33px;
            height: 33px;
            border: 1px solid green;
        }
        @media print {
            @page {
                size:  A4 landscape;
                margin: 5px;
            }
        }
        .width-100{
            width: 99%;
        }

    .fancybox-img{
        width: 10%;
    }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
@endsection
<!-- End block -->

<!-- BEGIN PAGE CONTENT-->
@section('pageContent')
    <!-- Section header -->
    <section class="content-header">
        <ol class="breadcrumb">
            <li><a href="{{URL::route('user.dashboard')}}"><i class="fa fa-dashboard"></i> {{ __('Dashboard') }} </a></li>
            <li><a href="{{URL::route('parents.index')}}"><i class="fa fa-solid fa-user-group"></i> {{ __('Parent') }} </a></li>
            <li class="active"> {{ __('View') }} </li>
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
                        <small> {{ __('Details') }} </small>
                    </h1>
                    <div class="box-tools pull-right">
                        <div class="btn-group">
                            <a href="#"  class="btn-pr btn-sm-pr btn-print btnPrintInformation"><i class="fa fa-print"></i> {{ __('Print') }} </a>
                        </div>
                        <div class="btn-group">
                            @can('parents.edit')
                            <a href="{{URL::route('parents.edit',$parent->id)}}" class="btn-pr btn-sm-pr"><i class="fa fa-edit"></i> {{ __('Edit') }} </a>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="wrap-outter-box">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="box box-info">
                                <div class="box-body box-profile">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <form id="uploadForm" method="post" enctype="multipart/form-data" action="{{ route('parents.updateProfilePhoto', $parent->id) }}">
                                                @csrf
                                                <!-- Add a hidden field for the old profile_photo value -->
                                                <input type="hidden" name="old_profile_photo" value="{{ $parent->profile_photo }}">
                                                <input type="file" id="image_file" name="profile_photo" style="display: none">
                                            </form>
                                            <div class="position-relative text-center box-img">
                                                <div id="loadingSpinner" class="position-absolute top-50 start-50 translate-middle loading">
                                                    <i class="fa-solid fa-spinner fa-spin-pulse fa-2xl"></i>
                                                </div>
                                                <img class="profile-user-img img-fluid img-circle" src="@if($parent->profile_photo){{ Storage::url($parent->profile_photo) }} @else {{ asset('images/avatar.png')}} @endif">
                                                <button type="button" onclick="openFileUploader()" class="position-absolute top-0 text-right translate-left">
                                                    <i class="fas fa-pen-to-square"></i>
                                                </button>
                                            </div>

                                        </div>
                                        <div class="col-lg-9">

                                            <h3 class="profile-username text-center">{{$parent->family_name.' '.$parent->name}}</h3>
                                            <p class="text-muted text-center">{{$parentType}}</p>

                                            <ul class="list-group list-group-unbordered profile-log">
                                                <li class="list-group-item">
                                                    <strong><i class="fa fa-id-card margin-r-5"></i> {{ __('ID Card No') }} :</strong>
                                                    <a class="pull-right">{{$parent->id_card}}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <strong><i class="fa fa-phone margin-r-5"></i> {{ __('Phone No') }} :</strong>
                                                    <a class="pull-right">{{$parent->phone_no}}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <strong><i class="fa fa-envelope margin-r-5"></i> {{ __('Email') }} :</strong>
                                                    <a class="pull-right">{{$parent->email}}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <strong><i class="fa fa-user margin-r-5"></i> {{ __('Username') }} :</strong>
                                                    @if($parent->user)
                                                        @if ($parent->user_id)
                                                            <a class="pull-right" href="{{URL::route('user.edit',$parent->user_id)}}">{{$parent->user->username}}</a>
                                                        @endif
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{-- <form id="uploadForm" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="file" id="image_file" name="image_file" style="display: none">
                            <!-- Button to trigger file selection -->
                            <button type="button" class="btn btn-primary" onclick="openFileUploader()">Upload Image</button>
                        </form> --}}
                        @php
                            $tab = 'profile';
                            if(request()->has('tab'))
                            {
                                $tab = request()->get('tab');
                            }
                        @endphp
                        <div class="col-lg-12">
                                <div class="nav nav-tabs">
                                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true"> {{ __('Profile') }} </button>
                                    @can('student.show')
                                    <button class="nav-link" id="students-tab" data-bs-toggle="tab" data-bs-target="#nav-list-students" type="button" role="tab" aria-controls="nav-list-students" aria-selected="false"> {{ __('List Students') }} </button>
                                    @endcan

                                    <button class="nav-link d-none" id="parents-tab" data-bs-toggle="tab" data-bs-target="#nav-list-parents" type="button" role="tab" aria-controls="nav-list-parents" aria-selected="false"> {{ __('Parents Access') }} </button>

                                </div>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                    @include('backend.parent.tabs.profile',['parent' => $parent])
                                </div>
                                <div class="tab-pane fade" id="nav-list-students" role="tabpanel" aria-labelledby="students-tab">
                                    @include('backend.parent.tabs.list_students',['parent' => $parent])
                                </div>
                                <div class="tab-pane fade " id="nav-list-parents" role="tabpanel" aria-labelledby="parents-tab">
                                    @include('backend.parent.tabs.parent_access',['parent' => $parent])
                                </div>
                            </div>
                        </div>
                    </div>
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
    <script src="{{asset('portal/js/dropzone.js')}}"></script>
    <script src="{{asset('portal/js/cropper.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            $( "#id_card_guardian" ).autocomplete({
                source: "{{ URL::route('search-id-card-guardian') }}",
                minLength: 2,
                select: function(event, ui) {

                    $("#guardian_name").val(ui.item.name);
                    $("#guardian_family_name").val(ui.item.family_name);
                    $("#guardian_passport").val(ui.item.passport_no);
                    $("#guardian_gender").val(ui.item.gender);

                    $("#guardian_gender").val(ui.item.gender);
                    $('#guardian_gender').select2().trigger('change');

                    $("#guardian_dob").val(ui.item.dob);
                    $("#guardian_email").val(ui.item.email);
                    $("#guardian_phone").val(ui.item.phone_no);
                    $("#guardian_nationality").val(ui.item.nationality);
                    $("#guardian_occupation").val(ui.item.occupation);
                    $("#guardian_company_name").val(ui.item.company_name);
                    $("#guardian_company_city").val(ui.item.company_city);
                    $("#guardian_company_country").val(ui.item.company_country);
                    $("#guardian_address").val(ui.item.address);

                    var imgUrl = window.location.origin+"/get-face-trained/parent/"+ui.item.org_id+"/"+ui.item.photo;

                    data = {
                        name      : ui.item.photo,
                        imageURL  :imgUrl
                    }
                    $(".dz-preview").remove();
                    // $(".dz-message").show();
                    previewThumbailFromUrl(data);

                    <!--$("#display_images_father_photo").attr("src","{{asset('storage/parents')}}"+"/"+ui.item.photo);-->

                }
            });

            function previewThumbailFromUrl(opts) {
                var myDropzone = Dropzone.forElement("#dZUpload");
                var mockFile = {
                    name: opts.name,
                    size: opts.size,
                    accepted: true,
                    kind: 'image',
                    upload: {
                        filename: opts.name,
                    },
                    dataURL: opts.imageURL,
                };
                myDropzone.files.push(mockFile);
                myDropzone.emit("addedfile", mockFile);
                myDropzone.createThumbnailFromUrl(
                    mockFile,
                    myDropzone.options.thumbnailWidth,
                    myDropzone.options.thumbnailHeight,
                    myDropzone.options.thumbnailMethod,
                    true,
                    function(thumbnail) {
                        myDropzone.emit('thumbnail', mockFile, thumbnail);
                        myDropzone.emit("complete", mockFile);
                    }
                );
            }

            let isDelete = 1;
            $('#frm_child_picker').submit(function(e) {
                // $('#is_delete').val('0');
                isDelete = 0;
            });
            Dropzone.autoDiscover = false;
            $(window).on('beforeunload', function(){
                if(isDelete==1){
                    $.ajax({
                        type: "POST",
                        url: "{{route('face.delete.temp')}}",
                        data: {'temp_path' : $("#temp_path").val() },
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

        Dropzone.options.dZUpload =
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
                        formData.append("temp_path", $("#temp_path").val());
                    });
                    this.on("removedfile", function (file) {
                        $.post({
                            url: "{{route('face.delete.tempfile')}}",
                            data: {filename: file.name,temp_path:$("#temp_path").val(), _token: $('meta[name="csrf-token"]').attr('content')},
                            dataType: 'json',
                            success: function (data) {

                            }
                        });
                    });
                }
            };

    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.btnPrintInformation').click(function () {
                $('ul.nav-tabs li:not(.active)').addClass('no-print');
                $('ul.nav-tabs li.active').removeClass('no-print');
                window.print();
            });

            @if($tab)
                $('#{{$tab}}').trigger("click");
            @endif

            Generic.initCommonPageJS();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#btn-add-student').on('click',function(){
                var parentId = {{$parent->id}};
                var t = $('#datatable-student').DataTable({
                    processing: true,
                    serverSide: true,
                    bLengthChange: false,
                    ajax:{
                        url: "{!!  route('parent.students',$parent->id, Request::query()) !!}",
                        data: {'parent_id' : parentId },
                    },
                    columns:[
                        {
                            data: 'id',
                            name: 'id',
                        },
                        {
                            data: 'photo',
                            name: 'photo',
                            orderable: false
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'name_latin',
                            name: 'name_latin'
                        },
                        {
                            data: 'gender',
                            name: 'gender'
                        },

                        {
                            data: 'phone',
                            name: 'phone'
                        },

                        {
                            data: 'action',
                            name: 'action',
                            orderable: false
                        },
                    ],
                    destroy : true
                });

                $('#btn-add-students').click(function(e){
                    e.preventDefault();

                    var studentIds = [];
                    $('input[type="checkbox"]:checked').each(function(){
                        studentIds.push($(this).attr("data-studentID"));
                    });
                    $(this).prop('disabled', true);
                    $.ajax({
                        type:'POST',
                        url : "{!! route('parent.student.create') !!}",
                        data:{
                            'parent_id' : parentId,
                            'student_ids' : studentIds,
                        },
                        success: function(response){
                            if(response.message){
                                swal({
                                        text: response.message,
                                        type: "success",
                                        timer: 2000
                                    });
                                //$('#student-records').hide();
                                $('#datatable-student').DataTable().ajax.reload();
                                window.location.href = "{{ url()->to('/') }}/parents/"+parentId+"?tab=students-tab";
                            } else {
                                swal({
                                        title: 'Something went wrong',
                                        text: response.error,
                                        type: "warning",
                                        timer: 2000
                                    });
                            }
                        },
                        error: function(error){
                            $('#btn-add-students').prop('disabled', false);
                            swal({
                                title: 'Something went wrong',
                                text: error,
                                type: "warning",
                                timer: 2000
                            });
                        }
                    });
                });
            });

            $('.unlinke-parent-student').on('click',function(e){
                e.preventDefault()
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
                        $(this).closest('form').submit();
                    }
                });
            });

        });

        // document.getElementById('image_file').addEventListener('change', async function() {
        //     const selectedImage = document.getElementById('selectedImage');
        //     const fileInput = event.target;
        //     const file = fileInput.files[0];

        //     if (file) {
        //         const reader = new FileReader();
        //         reader.onload = function (e) {
        //             selectedImage.src = e.target.result;
        //         };
        //         reader.readAsDataURL(file);

        //         var formData = new FormData(document.getElementById('uploadForm'));
        //         formData.append('_token', '{{ csrf_token() }}');

        //         try {
        //             const response = await fetch("{{ route('parents.updateProfilePhoto', $parent->id) }}", {
        //                 method: "POST",
        //                 body: formData,
        //                 headers: {
        //                     'X-CSRF-Token': '{{ csrf_token() }}',
        //                 }
        //             });

        //             const data = await response.json();

        //             console.log(data);

        //             if (data.success) {
        //                 // Handle success
        //             } else {
        //                 // Handle failure or unauthorized
        //             }
        //         } catch (error) {
        //             console.error('Error:', error);
        //         }
        //     }
        // });

    function openFileUploader() {
        document.getElementById('image_file').click();
    }
    document.getElementById('image_file').addEventListener('change', function () {
        // Show loading spinner
        document.getElementById('loadingSpinner').style.display = 'block';

        const form = document.getElementById('uploadForm');
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            // Handle success or error responses as needed
        })
        .catch(error => {
            console.error('Error:', error);
            // Handle error
        })
        .finally(() => {
            // Hide loading spinner after the request is complete
            document.getElementById('loadingSpinner').style.display = 'none';
        });
    });
    document.getElementById('image_file').addEventListener('change', function() {
        document.getElementById('uploadForm').submit();
    });

    </script>

@endsection
<!-- END PAGE JS-->
