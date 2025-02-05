<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle') User @endsection
<!-- End block -->

<!-- Page body extra class -->
@section('bodyCssClass') @endsection
<!-- End block -->

<!-- BEGIN PAGE CONTENT-->
@section('pageContent')
    <!-- Section header -->
    <section class="content-header">
        <ol class="breadcrumb">
            <li><a href="{{URL::route('user.dashboard')}}"><i class="fa fa-dashboard"></i> {{ __('Dashboard') }} </a></li>
            <li> {{ __('Administrator') }} </li>
            <li><a href="{{URL::route('administrator.user_index')}}">{{ __('System User') }} </a></li>
            <li class="active">@if($user) {{ __('Update') }} @else {{ __('Add') }} @endif</li>
        </ol>
    </section>
    <!-- ./Section header -->
    <!-- Main content -->
    <section class="content">
        <form novalidate id="entryForm" action="@if($user) {{URL::Route('administrator.user_update', $user->id)}} @else {{URL::Route('administrator.user_store')}} @endif" method="post" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
                <div class="col-md-12">
                    <div class="wrap-outter-header-title">
                        <h1>
                            {{ __('System User') }}
                            <small>@if($user) {{ __('Update') }} @else {{ __('Add New') }} @endif</small>
                        </h1>

                        <div class="box-tools pull-right">
                            <a href="{{URL::route('administrator.user_index')}}" class="btn btn-default"> {{ __('Cancel') }} </a>
                            <button type="submit" class="btn btn-info pull-right text-white"><i class="fa @if($user) fa-refresh @else fa-plus-circle @endif"></i> @if($user) {{ __('Update') }} @else {{ __('Save') }} @endif</button>

                        </div>
                    </div>
                </div>
            </div>
            <div class="wrap-outter-box">
                <div class="box-header">
                </div>
                <div class="box-body">
                    @csrf
                    <!-- Organization -->
                    @auth
                    {{-- @if(auth()->user()->newRole->role_id === AppHelper::USER_SUPER_ADMIN) --}}
                    {!! AppHelper::selectOrg($errors, $user->org_id ?? 0) !!}
                    {{-- @endif --}}
                    @endauth
                    <!-- End organization -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                                <label for="status"> {{ __('Status') }} <span class="text-danger">*</span></label>
                                <select name="status" class="form-select bg-light" id="status">
                                    <option value="1" {{ old('status', optional($user)->status) == 1 || is_null($user) ? 'selected' : '' }}> {{ __('Active') }} </option>
                                    <option value="0" {{ old('status', optional($user)->status) == 0 && !is_null($user) ? 'selected' : '' }}> {{ __('Inactive') }} </option>
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                                <label for="name"> {{ __('Name') }} <span class="text-danger">*</span></label>
                                <input autofocus type="text" class="form-control" name="name" placeholder="name" value="@if($user){{ $user->name }}@else{{old('name')}}@endif" required minlength="2" maxlength="255">
                                <span class="fa fa-info form-control-feedback"></span>
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                                <label for="email"> {{ __('Email') }} <span class="text-danger">*</span></label>
                                <input  type="email" class="form-control" name="email"  placeholder="email address" value="@if($user){{$user->email}}@else{{old('email')}}@endif" maxlength="100" required autocomplete="new-password">
                                <span class="fa fa-envelope form-control-feedback"></span>
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                                <label for="phone_no"> {{ __('Phone/Mobile No.') }} </label>
                                <input  type="text" class="form-control" name="phone_no" placeholder="phone or mobile number" value="@if($user){{$user->phone_no}}@else{{old('phone_no')}}@endif" maxlength="15">
                                <span class="fa fa-phone form-control-feedback"></span>
                                <span class="text-danger">{{ $errors->first('phone_no') }}</span>
                            </div>
                        </div>
                    </div>
                    @if(!$user)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label for="username"> {{ __('Username') }} <span class="text-danger">*</span></label>
                                <input  type="text" class="form-control" value="" name="username" required minlength="5" maxlength="255" autocomplete="new-password">
                                <span class="glyphicon glyphicon-info-sign form-control-feedback"></span>
                                <span class="text-danger">{{ $errors->first('username') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label for="password"> {{ __('Password') }} <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password" placeholder="Password" required minlength="6" maxlength="50" autocomplete="new-password">
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            </div>
                        </div>
                    </div>
                        @endif

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
            Generic.initCommonPageJS();
        });
    </script>
@endsection
<!-- END PAGE JS-->
