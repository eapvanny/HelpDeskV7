<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle')
    Support
@endsection
<!-- End block -->

<!-- Page body extra class -->
@section('bodyCssClass')
@endsection
<!-- End block -->
@section('extraStyle')
    <style>

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
            <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ __('Dashboard') }} </a></li>
            <li><a href="{{ URL::route('contact.index') }}"> {{ __('Contact') }} </a></li>
            <li class="active">
                @if ($contact)
                    {{ __('Update') }}
                @else
                    {{ __('Add') }}
                @endif
            </li>
        </ol>
    </section>
    <!-- ./Section header -->
    <!-- Main content -->
    <section class="content">

        <form novalidate id="entryForm"
            action="@if ($contact) {{ URL::Route('contact.update', $contact->id) }} @else {{ URL::Route('contact.store') }} @endif"
            method="post" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
                <div class="col-md-12">
                    <div class="wrap-outter-header-title">
                        <h1>
                            {{ __('Support') }}
                            <small>
                                @if ($contact)
                                    {{ __('Update') }}
                                @else
                                    {{ __('Add New') }}
                                @endif
                            </small>
                        </h1>

                        <div class="box-tools pull-right">
                            <a href="{{ URL::route('contact.index') }}" class="btn btn-default">{{ __('Cancel') }}</a>
                            <button type="submit" class="btn btn-info pull-right text-white"><i
                                    class="fa @if ($contact) fa-refresh @else fa-plus-circle @endif"></i>
                                @if ($contact)
                                    {{ __('Update') }}
                                @else
                                    {{ __('Add') }}
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wrap-outter-box">
                <div class="box-body">
                    @csrf
                    @if ($contact)
                        @method('PUT')
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label for="id_card"> {{ __('ID No.') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="id_card" placeholder="id_card"
                                    value="@if ($contact) {{ $contact->id_card }}@else{{ old('id_card') }} @endif"
                                    required minlength="3" maxlength="10">
                                <span class="fa fa-info form-control-feedback"></span>
                                <span class="text-danger">{{ $errors->first('id_card') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label for="link_telegram"> {{ __('Link Telegram') }} <span class="text-danger">*</span></label>
                                <input autofocus type="text" class="form-control" name="link_telegram" placeholder="link_telegram"
                                    value="@if ($contact) {{ $contact->subject }}@else{{ old('subject') }} @endif"
                                    required minlength="2" maxlength="255">
                                <span class="fa fa-info form-control-feedback"></span>
                                <span class="text-danger">{{ $errors->first('link_telegram') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label for="code">{{ __('Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder=""
                                    value="@if (old('name')) {{ old('name') }}@elseif($contact){{ $contact->name }} @endif"
                                    minlength="1" maxlength="50" required />
                                <span class="fa fa-info form-control-feedback"></span>
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label for="name_in_latin">{{ __('Name in Latin') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name_in_latin" class="form-control" placeholder=""
                                    value="@if (old('name_in_latin')) {{ old('name_in_latin') }}@elseif($contact){{ $contact->name_in_latin }} @endif"
                                    minlength="1" maxlength="50" required />
                                <span class="fa fa-info form-control-feedback"></span>
                                <span class="text-danger">{{ $errors->first('name_in_latin') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group has-feedback">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="photo"> {{ __('Photo') }} <br /><span
                                            class="text-muted fst-italic">{{ __('(Files: jpeg, jpg, or png, min dimension: 50
                                                                                                                                            x 50 pixel, 2Mb max size)') }}</span></label>
                                    <input type="file" class="form-control" accept=".jpeg, .jpg, .png"
                                        name="photo" placeholder="{{ __('Photo image') }}">
                                    <span class="glyphicon glyphicon-open-file form-control-feedback"
                                        style="top:35px;"></span>
                                    <span class="text-danger">{{ $errors->first('photo') }}</span>
                                </div>
                                <div class="col-md-2">
                                    @if ($contact && isset($contact->photo))
                                        <img src="{{ $contact->photo ? asset('storage/' . $contact->photo) : asset('images/avatar.jpg') }}"
                                            alt="Current Photo" style="max-height: 50px; margin-top: 40px">
                                        <input type="hidden" name="oldPhoto" value="{{ $contact->photo }}">
                                    @endif
                                </div>
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
        $(document).ready(function() {

        });
    </script>
@endsection
<!-- END PAGE JS-->
