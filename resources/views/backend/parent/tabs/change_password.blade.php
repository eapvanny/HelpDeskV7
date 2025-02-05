<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle') Change Password @endsection
<!-- End block -->

<!-- Page body extra class -->
@section('bodyCssClass') @endsection
<!-- End block -->

<!-- BEGIN PAGE CONTENT-->
@section('pageContent')
    <!-- Main content -->
    <section class="content-header">
        
        <ol class="breadcrumb">
            <li><a href="{{URL::route('user.dashboard')}}"><i class="fa fa-dashboard"></i> {{ __('Dashboard') }} </a></li>
            <li class="active"> {{ __('Change Password') }} </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="wrap-outter-header-title">
                    <h1>
                        {{ __('Change Password Parent') }}
                    </h1>
                </div>
                <div class="wrap-outter-box">  
                    <div class="box-body">
                        <form id="changePasswordForm" action="{{ route('p_update_password', ['id' => $parent->user_id]) }}" method="post" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $parent->id }}">
                            <div class="form-group has-feedback">
                                <label for="oldpassword">{{__('Old Password')}} .</label>
                                <input type="password" class="form-control" name="old_password" placeholder="Old Password" required minlength="6" maxlength="50">
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                <span class="text-danger">{{ $errors->first('old_password') }}</span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="newpassword">{{ __('New Password.') }}</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="New Password" required minlength="6" maxlength="50">
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="confirmpassword">{{ __('Confirm Password.') }}</label>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required minlength="6" maxlength="50">
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                            </div>

                            <br>
                            <a href="{{URL::route('user.dashboard')}}" class="btn btn-default btnCancel">Cancel</a>
                            <button type="submit" class="btn btn-info pull-right text-white"><i class="fa fa-refresh"></i> {{ __('Update') }} </button>
                        </form>
                    </div>
                            
                </div>
                
            </div>
            
            <div class="col-md-3"></div>

                <!-- /.box -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection
<!-- END PAGE CONTENT-->

<!-- BEGIN PAGE JS-->
@section('extraScript')
    <script type="text/javascript">
        $(document).ready(function () {
            $("#changePasswordForm").validate({
            rules: {
                old_password: {
                    required: true,
                    minlength: 6,
                    maxlength: 255
                },
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 255
                },
                password_confirmation: {
                    required: true,
                    minlength: 6,
                    maxlength: 255,
                    equalTo: "#password"

                },

            },
            messages: {
                password: {
                    required: "Please enter your new password",
                    maxlength: "Your password must not greater than 255 characters"
                },
                old_password: {
                    required: "Please enter your old password",
                    maxlength: "Your password must not greater than 255 characters"
                },
                password_confirmation: {
                    required: "Please confirm your password",
                    maxlength: "Your password must not greater than 255 characters",
                    equalTo: "Password didn't match!"
                },

            },
            errorElement: "em",
            errorPlacement: function (error, element) {
                // Add the `help-block` class to the error element
                error.addClass("help-block");
                error.insertAfter(element);

            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents(".form-group").addClass("has-error").removeClass("has-success");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".form-group").addClass("has-success").removeClass("has-error");
            }
        });
        });
    </script>
@endsection
<!-- END PAGE JS-->
