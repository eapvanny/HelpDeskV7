<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle') Subject @endsection
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
            <li>{{ __('Master Data') }}</li>
            <li><a href="{{URL::route('master_subject.index')}}">{{ __('Subjects') }} </a></li>
            <li class="active">@if($master_subject) {{ __('Update') }} @else {{ __('Add') }} @endif</li>
        </ol>
    </section>
    <!-- ./Section header -->

    <!-- Main content -->
    <section class="content">
        <form  id="entryForm" action="@if($master_subject) {{URL::Route('master_subject.update', $master_subject->id)}} @else {{URL::Route('master_subject.store')}} @endif" method="post" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
                <div class="col-md-12">
                    <div class="wrap-outter-header-title">
                        <h1>
                            {{ __('Subject') }}
                        </h1>
                        <div class="box-tools pull-right">
                            <a href="{{URL::route('master_subject.index')}}" class="btn btn-default"> {{ __('Cancel') }} </a>
                            <button type="submit" class="submitClick btn btn-info pull-right text-white"><i class="fa @if($master_subject) fa-refresh @else fa-check-circle @endif"></i> @if($master_subject) {{ __('Update') }} @else {{ __('Save') }} @endif</button>
                            @if(!$master_subject)
                                <button type="submit" class="submitClick submitAndContinue & Add Newtinue btn btn-success text-white">
                                <i class="fa fa-plus-circle"></i> {{ __('Save & Add New') }}
                                </button>
                                <div class="boxfooter"></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @csrf
            @if($master_subject)
                <input type="hidden" name="_method" value="PUT">
            @endif

            <div class="wrap-outter-box">
                <div class="box box-info">

                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label for="code">{{ __('Code') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control" placeholder="SJ001" value="@if(old('code')){{old('code')}}@elseif($master_subject){{ $master_subject->code }}@endif" minlength="1" maxlength="50" required />
                                    <span class="fa fa-info form-control-feedback"></span>
                                    <span class="text-danger">{{ $errors->first('code') }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label for="code">{{ __('Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" placeholder={{__("General Study")}} 
                                    value="@if(old('name')){{old('name')}}@elseif($master_subject){{ $master_subject->name }}@endif" minlength="1" maxlength="50" required />
                                    <span class="fa fa-info form-control-feedback"></span>
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label for="code">{{ __('Name in Latin') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name_in_latin" class="form-control" placeholder={{__("General Study")}} value="@if(old('name_in_latin')){{old('name_in_latin')}}@elseif($master_subject){{ $master_subject->name_in_latin }}@endif" minlength="1" maxlength="50" required />
                                    <span class="fa fa-info form-control-feedback"></span>
                                    <span class="text-danger">{{ $errors->first('name_in_latin') }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 col-xl-4">
                                <div class="form-group has-feedback">
                                    <label for="major_id">{{ __('Major') }} <span class="text-danger">*</span>
                                    </label>
                                    {!! Form::select('major_id', $majors, old('major_id') ? old('major_id') : $master_subject->major_id ?? request()->query('major_id') , [ 'placeholder' => __('pick a major'), 'id' => 'major_id','class' => 'form-control select2', 'required' => 'true']) !!}
                                    <span class="form-control-feedback"></span>
                                    <span class="text-danger">{{ $errors->first('major_id') }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    <label for="code">{{ __('Abbreviation') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="abbreviation" class="form-control" placeholder={{__("General Study")}} value="@if(old('abbreviation')){{old('abbreviation')}}@elseif($master_subject){{ $master_subject->abbreviation }}@endif" minlength="1" maxlength="50" required />
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

            

        });
        



    </script>

    <script type="text/javascript">

        $(document).ready(function () {
            Generic.initCommonPageJS();
            @if(isset($master_subject) && isset($master_subject->major_id))
                $('#major_id').prop('disabled', true);
            @endif
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


        // tinymce.init({
        //     selector:'textarea.tinymce'
        // });
    </script>

@endsection
<!-- END PAGE JS-->

