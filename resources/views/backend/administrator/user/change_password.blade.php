<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle') Reset User Password @endsection
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
            <li> {{ __('Administrator') }} </li>
            <li class="active"> {{ __('Reset User Password') }} </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <form novalidate id="changePasswordForm" action="{{URL::route('administrator.user_password_reset')}}" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12">
                    <div class="wrap-outter-header-title">
                        <h1>
                            {{ __('Reset User Password') }}
                        </h1>

                        <div class="box-tools pull-right">
                            <a href="{{URL::route('user.dashboard')}}" class="btn btn-default btnCancel"> {{ __('Cancel') }} </a>
                            <button type="submit" class="btn btn-info pull-right text-white"><i class="fa fa-refresh"></i> {{ __('Reset') }} </button>
                        </div>
                    </div>
                </div>
            </div>
            @csrf
            <div class="wrap-outter-box">
                <div class="box-body">
                    {{-- <div class="form-group has-feedback">
                        <label for="role_id"> {{ __('User') }}.
                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Set a user"></i>
                        </label>
                        {!! Form::select('user_id', $users, null , ['placeholder' => 'Pick a user...','class' => 'form-control select2', 'required' => 'true']) !!}
                        <span class="form-control-feedback"></span>
                        <span class="text-danger">{{ $errors->first('user_id') }}</span>
                    </div> --}}
                    <div class="form-group has-feedback">
                        <label for="user_id">{{ __('User') }}.
                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Set a user"></i>
                        </label>
                        <select id="user_id" name="user_id" class="form-control select2" required>
                            <option value="">{{ __('Pick a User') }}...</option>
                        </select>
                        <span class="form-control-feedback"></span>
                        <span class="text-danger">{{ $errors->first('user_id') }}</span>
                    </div>
                    <div class="form-group has-feedback">
                        <label for="newpassword">{{ __('New Password') }}.</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="{{ __('New Password') }}" required minlength="6" maxlength="50">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                    </div>
                    <div class="form-group has-feedback">
                        <label for="confirmpassword">{{ __('Confirm Password') }}.</label>
                        <input type="password" class="form-control" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" required minlength="6" maxlength="50">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                    </div>
                    <br>
                </div>
            </div>
        </form>
    </section>
    <!-- /.content -->
@endsection
<!-- END PAGE CONTENT-->

<!-- BEGIN PAGE JS-->
@section('extraScript')
    <script type="text/javascript">
        $(document).ready(function () {
            Login.resetPassword();

            $('#user_id').select2({
                placeholder: "{{ __('Pick a User') }}...",
                minimumInputLength: 0,  // Allows the dropdown to show results even if nothing is typed
                ajax: {
                    url: "{{ route('administrator.user_password_reset_search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term || '' // Send the search term, or an empty string if no search term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.name
                                };
                            })
                        };
                    },
                    cache: true
                },
                allowClear: true  // Allow the user to clear the selection if needed
            });

            // Trigger the dropdown to show the first 5 users when it's first opened
            $('#user_id').on('select2:open', function () {
                if (!$.trim($('#user_id').text())) {  // If no options are currently displayed
                    $(this).trigger('select2:selecting', {
                        data: {
                            id: '',
                            text: ''
                        }
                    });
                }
            });
        });
    </script>
@endsection
<!-- END PAGE JS-->
