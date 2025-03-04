<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    @php

        /*@if(isset(auth()->user()->organization->short_name) && !empty(auth()->user()->organization->short_name)){{auth()->user()->organization->short_name}} @else HiTECH @endif*/
        $title = (isset(auth()->user()->organization->short_name) && !empty(auth()->user()->organization->short_name))?auth()->user()->organization->short_name : 'HiTECH';
        //dd($title);
    @endphp
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="@if(isset(auth()->user()->organization->name) && !empty(auth()->user()->organization->name)){{auth()->user()->organization->name}}@else HiTECH @endif">
    <meta name="keywords" content="school,college,management,result,exam,attendace,hostel,admission,events">
    <meta name="author" content="H.R.Shadhin">
    <title>{{$title}} | @yield('pageTitle')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon png -->
    <link rel="icon" href="@if(isset(auth()->user()->organization->favicon) && !empty(auth()->user()->organization->favicon)){{asset('storage/logo/'.auth()->user()->organization->favicon)}} @else{{ asset('images/Hi-Tech_Water_Logo.png') }}@endif" type="image/png">
    <!-- Pace loading -->
    <script src="{{ asset(mix('/js/pace.js')) }}"></script>
    <link href="{{ asset(mix('/css/pace.css')) }}" rel="stylesheet" type="text/css">
    <!-- vendor libraries CSS -->
    <link href="{{ asset(mix('/css/vendor.css')) }}" rel="stylesheet" type="text/css">
    <!-- theme CSS -->
    <link href="{{ asset(mix('/css/theme.css')) }}" rel="stylesheet" type="text/css">
    <!-- app CSS -->
    <link href="{{ asset(mix('/css/app.css')) }}" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <!-- Child Page css goes here  -->
    @yield("extraStyle")
    <!-- Child Page css -->

</head>

<body class="hold-transition @yield('bodyCssClass')">
<div class="overlay-loader">
    <div class="loader" ></div>
</div>
    <!-- BEGIN CHILD PAGE-->
    @yield('pageContent')
	<!-- END CHILD PAGE-->

    <!-- webpack menifest js -->
    <script src="{{ asset(mix('/js/manifest.js')) }}"></script>
     <!-- vendor libaries js -->
    <script src="{{ asset(mix('/js/vendor.js')) }}"></script>
     <!-- app js -->
    <script src="{{ asset(mix('/js/app.js')) }}"></script>

     <!-- Extra js from child page -->
     @yield("extraScript")
    <!-- END JAVASCRIPT -->
</body>

</html>
