<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle') Statuss @endsection
<!-- End block -->

<!-- Page body extra class -->
@section('bodyCssClass') @endsection
<!-- End block -->
@section('extraStyle')
    <style>
        fieldset .form-group{
            margin-bottom: 0px;
        }
        fieldset .iradio .error,fieldset .icheck .error{
            display: none !important;
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
            border: 1px solid #9a9a9a;
            margin: 2px 2px;
            padding: .35em .625em .75em;
            margin-top: 4px;
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
            <li><a href="{{URL::route('dashboard.index')}}"><i class="fa fa-dashboard"></i> {{ __('Dashboard') }} </a></li>
            <li><a href="{{URL::route('status.index')}}"> {{ __('Status') }} </a></li>
            <li class="active">@if($status) {{__('Update')}} @else {{ __('Add') }} @endif</li>
        </ol>
    </section>
    <!-- ./Section header -->
    <!-- Main content -->
    <section class="content">
        <form novalidate id="entryForm" action="{{URL::Route('status.update', $status->id)}} " method="post" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
                <div class="col-md-12">
                    <div class="wrap-outter-header-title">
                        <h1>
                            {{ __('Status') }}
                            <small>@if($status) {{__('Update')}} @else {{ __('Add New') }} @endif</small>
                        </h1>

                        <div class="box-tools pull-right">
                            <a href="{{URL::route('status.index')}}" class="btn btn-default">{{__('Cancel')}}</a>
                            <button type="submit" class="btn btn-info pull-right text-white"><i class="fa @if($status) fa-refresh @else fa-plus-circle @endif"></i> @if($status) {{__('Update')}} @else {{__('Add')}} @endif</button>
                        </div>
                    </div>
                </div>
            </div>    
            <div class="wrap-outter-box">
            <input id="org_detail" type="hidden" name="org_detail" value="">
            <div class="box-body">
                @csrf
                @if($status)
                    @method('PUT')
                @endif
                <div class="row">
                    <div class="col-md-6 col-xl-3">
                        <div class="form-group has-feedback">
                            <label for="status">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <input type="text" name="status" class="form-control" placeholder="" value="@if(old('status')){{old('status')}}@elseif($status){{ $status->name }}@endif" minlength="1" maxlength="50" required />
                            <span class="fa fa-info form-control-feedback"></span>
                            <span class="text-danger">{{ $errors->first('status') }}</span>
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
        $(document).ready(function () {
           
        });
    </script>
@endsection
<!-- END PAGE JS-->
