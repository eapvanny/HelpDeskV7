<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle') Shift @endsection
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
            <li><a href="{{URL::route('shift.index')}}"> {{ __('Shift') }} </a></li>
            <li class="active">@if($shift) Update @else {{ __('Add') }} @endif</li>
        </ol>
    </section>
    <!-- ./Section header -->

    <!-- Main content -->
    <section class="content">
        <form  id="entryForm" action="@if($shift) {{URL::Route('shift.update', $shift->id)}} @else {{URL::Route('shift.store')}} @endif" method="post" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
                <div class="col-md-12">
                    <div class="wrap-outter-header-title">
                        <h1>
                            {{ __('Shift') }}
                        </h1>
                        <div class="action-btn-top none_fly_action_btn">
                            <a href="{{URL::route('shift.index')}}" class="btn btn-default"> {{ __('Cancel') }} </a>
                                <button type="submit" class="btn btn-info pull-right text-white"><i class="fa @if($shift) fa-refresh @else fa-plus-circle @endif"></i>@if($shift) {{ __('Update') }} @else {{ __('Save') }} @endif</button>
                            @if(!$shift)
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
            @if($shift)
                <input type="hidden" name="_method" value="PUT">
            @endif
            <div class="wrap-outter-box">
                <div class="box box-info">

                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">

                            <div class="col-md-6 col-lg-4 col-xl-3 d-none">
                                <div class="form-group has-feedback">
                                    <label for="code">{{ __('Code') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control" placeholder="G7" value="@if(old('code')){{old('code')}}@elseif($shift){{ $shift->code }}@endif" minlength="1" maxlength="50" required />
                                    <span class="fa fa-info form-control-feedback"></span>
                                    <span class="text-danger">{{ $errors->first('code') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 col-xl-4">
                                <div class="form-group has-feedback">
                                    <label for="name">{{ __('Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" placeholder="G7" value="@if(old('name')){{old('name')}}@elseif($shift){{ $shift->name }}@endif" minlength="1" maxlength="50" required />
                                    <span class="fa fa-info form-control-feedback"></span>
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 col-xl-4">
                                <div class="form-group has-feedback">
                                    <label for="name_in_latin">{{ __('Name in Latin') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name_in_latin" class="form-control" placeholder="G7" value="@if(old('name_in_latin')){{old('name_in_latin')}}@elseif($shift){{ $shift->name_in_latin }}@endif" minlength="1" maxlength="50" required />
                                    <span class="fa fa-info form-control-feedback"></span>
                                    <span class="text-danger">{{ $errors->first('name_in_latin') }}</span>
                                </div>
                            </div>
                            <div class="col-12 d-none">
                                <label class="form-label d-block">{{ __('Select Days') }}</label>
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="checkbox" {{ (optional($shift)->monday ? 'checked' : '') }} name="monday" value="1">
                                    <label class="form-check-label" for="monday">{{ __('Monday') }}</label>
                                </div>
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="checkbox" {{ (optional($shift)->tuesday ? 'checked' : '') }} name="tuesday" value="1">
                                    <label class="form-check-label" for="tuesday">{{ __('Tuesday') }}</label>
                                </div>
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="checkbox" {{ (optional($shift)->wednesday ? 'checked' : '') }} name="wednesday" value="1">
                                    <label class="form-check-label" for="wednesday">{{ __('Wednesday') }}</label>
                                </div>
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="checkbox" {{ (optional($shift)->thursday ? 'checked' : '') }} name="thursday" value="1">
                                    <label class="form-check-label" for="thursday">{{ __('Thursday') }}</label>
                                </div>
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="checkbox" {{ (optional($shift)->friday ? 'checked' : '') }} name="friday" value="1">
                                    <label class="form-check-label" for="friday">{{ __('Friday') }}</label>
                                </div>
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="checkbox" {{ (optional($shift)->saturday ? 'checked' : '') }} name="saturday" value="1">
                                    <label class="form-check-label" for="saturday">{{ __('Saturday') }}</label>
                                </div>
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="checkbox" {{ (optional($shift)->sunday ? 'checked' : '') }} name="sunday" value="1">
                                    <label class="form-check-label" for="sunday">{{ __('Sunday') }}</label>
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
            })    
        });




    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            // window.postUrl = '{{URL::Route("organization.status", 0)}}';
            // window.changeExportColumnIndex = 6;
            // window.excludeFilterComlumns = [0,2,3,5,6,7];
            MasterData.studyPeriodInit();

        });
    </script>

@endsection
<!-- END PAGE JS-->

