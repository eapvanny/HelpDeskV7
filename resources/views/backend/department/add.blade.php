<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle') Department @endsection
<!-- End block -->

@section('extraStyle')
    <link rel="stylesheet" href="{{asset('portal/css/dropzone.css')}}">
    <link href="{{asset('portal/css/cropper.css')}}" rel="stylesheet"/>
@endsection
<!-- BEGIN PAGE CONTENT-->
@section('pageContent')
    <!-- Section header -->
    <section class="content-header">
        <ol class="breadcrumb">
            <li><a href="{{URL::route('dashboard.index')}}"><i class="fa fa-dashboard"></i> {{ __('Dashboard') }} </a></li>
            <li> {{ __('Master Data') }} </li>
            <li><a href="{{URL::route('department.index')}}"> {{ __('Department') }} </a></li>
            <li class="active">@if($department) Update @else {{ __('Add') }} @endif</li>
        </ol>
    </section>
    <!-- ./Section header -->

    <!-- Main content -->
    <section class="content">
        <form  id="entryForm" action="@if($department) {{URL::Route('department.update', $department->id)}} @else {{URL::Route('department.store')}} @endif" method="post" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
                <div class="col-md-12">
                    <div class="wrap-outter-header-title">
                        <h1>
                            {{ __('Department') }}
                        </h1>
                        <div class="action-btn-top none_fly_action_btn">
                            <a href="{{URL::route('department.index')}}" class="btn btn-default"> {{ __('Cancel') }} </a>
                            <button type="submit" class="submitClick btn btn-info pull-right text-white "><i class="fa @if($department) fa-refresh @else fa-check-circle @endif"></i>@if($department) {{ __('Update') }} @else {{ __('Save') }} @endif</button>
                            @if(!$department)
                                <button type="submit" class="submitClick submitAndContinue btn btn-success text-white">
                                <i class="fa fa-plus-circle"></i> {{ __('Save & Add New') }}
                                </button>
                                <div class="boxfooter"></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @csrf
            @if($department)
                @method('PUT')
            @endif
            <div class="wrap-outter-box">
                <div class="box box-info">

                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">

                            <div class="col-md-6 col-xl-3">
                                <div class="form-group has-feedback">
                                    <label for="code">{{ __('Code') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control" placeholder="" value="@if(old('code')){{old('code')}}@elseif($department){{ $department->code }}@endif" minlength="1" maxlength="50" required />
                                    <span class="fa fa-info form-control-feedback"></span>
                                    <span class="text-danger">{{ $errors->first('code') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="form-group has-feedback">
                                    <label for="code">{{ __('Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" placeholder="" value="@if(old('name')){{old('name')}}@elseif($department){{ $department->name }}@endif" minlength="1" maxlength="50" required />
                                    <span class="fa fa-info form-control-feedback"></span>
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="form-group has-feedback">
                                    <label for="name_in_latin">{{ __('Name in Latin') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name_in_latin" class="form-control" placeholder="" value="@if(old('name_in_latin')){{old('name_in_latin')}}@elseif($department){{ $department->name_in_latin }}@endif" minlength="1" maxlength="50" required />
                                    <span class="fa fa-info form-control-feedback"></span>
                                    <span class="text-danger">{{ $errors->first('name_in_latin') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="form-group has-feedback">
                                    <label for="abbreviation">{{ __('Abbreviation') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="abbreviation" class="form-control" placeholder="" value="@if(old('abbreviation')){{old('abbreviation')}}@elseif($department){{ $department->abbreviation }}@endif" minlength="1" maxlength="50" required />
                                    <span class="fa fa-info form-control-feedback"></span>
                                    <span class="text-danger">{{ $errors->first('abbreviation') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
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

            $(".submitClick").on('click', function(){
                event.preventDefault();
                if ($(this).hasClass('submitAndContinue')) {
                    $(".boxfooter").append('<input type="hidden" name="saveandcontinue" value="1" />');
                }else {
                    $("input[name='saveandcontinue']").each(function(){
                        $(this).remove();
                    });
                }
                $("#entryForm").submit();
            });

        });
    </script>
@endsection
<!-- END PAGE JS-->

