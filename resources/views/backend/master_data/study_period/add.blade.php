<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle') Study Period @endsection
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
            <li><a href="{{URL::route('user.dashboard')}}"><i class="fa fa-dashboard"></i> {{ __('Dashboard') }} </a></li>
            <li> {{ __('Master Data') }} </li>
            <li><a href="{{URL::route('study_period.index')}}"> {{ __('Study Period') }} </a></li>
            <li class="active">@if($study_period) Update @else {{ __('Add') }} @endif</li>
        </ol>
    </section>
    <!-- ./Section header -->

    <!-- Main content -->
    <section class="content">
        <form  id="entryForm" action="@if($study_period) {{URL::Route('study_period.update', $study_period->id)}} @else {{URL::Route('study_period.store')}} @endif" method="post" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
                <div class="col-md-12">
                    <div class="wrap-outter-header-title">
                        <h1>
                            {{ __('Study Period') }}
                        </h1>
                        <div class="box-tools pull-right">
                            <a href="{{URL::route('study_period.index')}}" class="btn btn-default"> {{ __('Cancel') }} </a>
                            <button type="submit" class="btn btn-info pull-right text-white"><i class="fa @if($study_period) fa-refresh @else fa-check-circle @endif"></i>@if($study_period) {{ __('Update') }} @else {{ __('Save') }} @endif</button>
                            @if(!$study_period)
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
            @if($study_period)
                <input type="hidden" name="_method" value="PUT">
            @endif
            <div class="wrap-outter-box">
                <div class="box box-info">

                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label for="shift_id"> {{ __('Shift') }}
                                        <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Set Shift"></i>
                                        <a href="{{ URL::route('shift.create') }}" target="new"> {{ __('(Add more)') }} </a>
                                        <span class="text-danger">*</span>
                                    </label>
                                    {!! Form::select('shift_id', $shifts, old('shift_id') ? old('shift_id') : $study_period->shift_id ?? request()->query('shift_id'), ['placeholder' => __('Pick a shift'),'class' => 'form-control select2', 'required' => 'true']) !!}

                                    <span class="fa fa-calendar form-control-feedback"></span>
                                    <span class="text-danger">{{ $errors->first('shift_id') }}</span>
                                </div>
                            </div>
                            <div class="col-md-3 d-none">
                                <div class="form-group has-feedback">
                                    <label for="code">{{ __('Code') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control" placeholder="M-H1" value="@if(old('code')){{old('code')}}@elseif($study_period){{ $study_period->code }}@endif" minlength="1" maxlength="50" required />
                                    <span class="fa fa-info form-control-feedback"></span>
                                    <span class="text-danger">{{ $errors->first('code') }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label for="start_time">{{ __('Start Time') }} <span class="text-danger">*</span></label>
                                    <input type="time" name="start_time" class="form-control" placeholder="7" value="@if(old('start_time')){{old('start_time')}}@elseif($study_period){{ $study_period->start_time }}@endif" minlength="1" maxlength="50" required />
                                    <span class="fa fa-info form-control-feedback"></span>
                                    <span class="text-danger">{{ $errors->first('start_time') }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label for="end_time">{{ __('End Time') }} <span class="text-danger">*</span></label>
                                    <input type="time" name="end_time" class="form-control" placeholder="7" value="@if(old('end_time')){{old('end_time')}}@elseif($study_period){{ $study_period->end_time }}@endif" minlength="1" maxlength="50" required />
                                    <span class="fa fa-info form-control-feedback"></span>
                                    <span class="text-danger">{{ $errors->first('end_time') }}</span>
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

        });




    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            // window.postUrl = '{{URL::Route("organization.status", 0)}}';
            // window.changeExportColumnIndex = 6;
            // window.excludeFilterComlumns = [0,2,3,5,6,7];
            MasterData.studyPeriodInit();



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
                })
        });
    </script>

@endsection
<!-- END PAGE JS-->

