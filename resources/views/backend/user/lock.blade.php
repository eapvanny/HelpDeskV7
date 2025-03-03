<!-- Master page  -->
@extends('backend.layouts.front_master')

<!-- Page title -->
@section('pageTitle')
    Lock Screen
@endsection
<!-- End block -->

<!-- Page body extra class -->
@section('bodyCssClass')
    hold-transition lockscreen
@endsection
<!-- End block -->

<!-- BEGIN PAGE CONTENT-->
@section('pageContent')
    <div class="lockscreen-wrapper">
        <div class="lockscreen-logo">
            <a href="/">
                <img src="{{asset('images/Hi-Tech_Water_Logo.png')}}" style="width: 150px;">
            </a>
        </div>
        <!-- User name -->
        <div class="lockscreen-name mb-3">{{ $username }}</div>

        <!-- START LOCK SCREEN ITEM -->
        <div class="lockscreen-item">
            <!-- lockscreen image -->
            <div class="lockscreen-image">
                <img src="{{ $photo ? asset('storage/' . $photo) : asset('images/avatar.png') }}" alt="User Image">
            </div>

            <!-- /.lockscreen-image -->

            <!-- lockscreen credentials (contains the form) -->
            <form class="lockscreen-credentials" action="{{ URL::route('unlock') }}" method="post" enctype="multipart/form-data">
                <div class="input-group">
                    <input autofocus type="password" name="password" class="form-control" placeholder="password" required>
                    <input type="hidden" class="form-control" name="username" value="{{ $username }}">
                    @csrf
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center justify-content-center">
                        <i class="fa fa-arrow-right text-muted"></i>
                    </button>
                </div>
            </form>
            <!-- /.lockscreen credentials -->

        </div>
        <!-- /.lockscreen-item -->
        <div class="help-block text-center">
            {{ __('Enter your password to retrieve your session') }}
        </div>
        <div class="text-center mt-3 text-decoration-none">
            <a class="text-decoration-none" href="{{ URL::route('login') }}" style="background-color: dodgerblue; padding: 8px 10px; color:white; border-radius: 4px;"> {{ __('SignIn') }} </a>
        </div>
        <div class="lockscreen-footer text-center">
            @if (Session::has('success') || Session::has('error') || Session::has('warning'))
                <div class="row">
                    <div
                        class="alert @if (Session::has('success')) alert-success @elseif(Session::has('error')) alert-danger @else alert-warning @endif alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        @if (Session::has('success'))
                            <h5><i class="icon fa fa-check"></i>{{ Session::get('success') }}</h5>
                        @elseif(Session::has('error'))
                            <h5><i class="icon fa fa-ban"></i>{{ Session::get('error') }}</h5>
                        @else
                            <h5><i class="icon fa fa-warning"></i>{{ Session::get('warning') }}</h5>
                        @endif
                        </h5>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
<!-- END PAGE CONTENT-->

<!-- BEGIN PAGE JS-->
@section('extraScript')
    <script type="text/javascript">
        $(document).ready(function() {

        });
    </script>
@endsection
<!-- END PAGE JS-->
