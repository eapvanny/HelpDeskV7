<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle') Translations @endsection
<!-- End block -->
<!-- BEGIN PAGE CONTENT-->
@section('pageContent')
 <!-- Section header -->
 <section class="content-header">

    <ol class="breadcrumb">
        <li><a href="{{URL::route('user.dashboard')}}"><i class="fa fa-dashboard"></i> {{ __('Dashboard') }} </a></li>
        <li><a href="{{URL::route('translation.index')}}">{{ __('Translations') }} </a></li>
        <li class="active">@if($translations) {{ __('Update') }} @else {{ __('Add') }} @endif</li>
    </ol>
</section>
<!-- ./Section header -->
<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="wrap-outter-header-title">
                <h1>
                    {{ __('Translations') }}
                    <small> {{ __('List') }} </small>
                </h1>
                <div class="box-tools pull-right">
                    <?php
                            $isFilter = false;
                            if ($locale > 0) $isFilter = true;
                        ?>
                        <button id="filters" class="btn btn-outline-secondary @if($isFilter) active @endif">
                            <i class="fa icon-markmain"></i> {{ __('Document') }}
                        </button>

                    <button type="button" class="btn btn-primary btn-md" id="btnAddNew" data-bs-toggle="modal" data-bs-target="#editTranslateModal"><i class="fa-solid fa-plus"></i> {{ __('Add New') }}</button>
                </div>
            </div>

            <div class="wrap-outter-box">


                <div class="box-info">
                    <form action="{{ route('translation.index') }}" method="get">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="wrap_filter_form @if(!$isFilter) d-none @endif">
                                    <div class="row">
                                        <div class="">
                                    <div class="row">
                                        <div class="col-12 col-md-6 col-xxl-3">
                                            <label class="form-label" for="search_locale">{{ __('File Translate As') }}:</label>
                                            <select class="form-select select2 border-0 form-select-custom fs-6 text-secondary" aria-label="localization" id="search_locale" name="locale">
                                                @foreach($locales as $key)
                                                    <option {{ ($key == $locale ? 'selected' : '') }} value="{{$key}}">{{ $key == 'en' ? __('English') : ($key == 'kh' ? __('Khmer'): '')}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mt-2">
                                            <button id="apply_filter" class="btn btn-outline-secondary btn-sm float-end">
                                                <i class="fa-solid fa-magnifying-glass"></i> {{ __('Apply') }}
                                            </butto>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row my-2">
                        <div class="col-12">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="fa-solid fa-file-import"></i> {{__('Import')}}
                        </button>

                            <a class="btn btn-primary btn-sm" href="{{ route('translation.export') }}"><i class="fa-solid fa-download"></i> {{__('Export')}}</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="listDataTableWithSearch" class="table table-bordered table-striped display responsive no-wrap" width="100%">
                            <thead>
                            <tr>
                                <th>{{ __('Text') }}</th>
                                <th>{{ __('Translate As') }}</th>
                                <th class="notexport" width="10%"> {{ __('Action') }} </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($translations as $trans)
                                <tr>
                                    <td>{{ $trans->item }}</td>
                                    <td>{!! $trans->text !!}</td>

                                    <td>
                                        <div class="change-action-item">
                                            <div class="btn-group">
                                                <a title="Edit" href="#" data-item="{{ $trans->item }}" data-text="{{ $trans->text }}" class="btn btn-primary btn-sm btn-edit-translate" data-bs-toggle="modal" data-bs-target="#editTranslateModal"><i class="fa fa-edit"></i></a>
                                            </div>
                                            <!-- todo: have problem in mobile device -->
                                            <div class="btn-group">
                                                <form  class="myAction" method="POST" action="{{URL::route('translation.destroy', $trans->id)}}">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                        <i class="fa fa-fw fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <div class="modal fade" id="editTranslateModal" tabindex="-1" aria-labelledby="editTranslateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded">
                <form action="{{ route('translation.store') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTranslateModalLabel">{{ __('Translations') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <input type="hidden" name="locale" value="{{ $locale }}">
                                <div class="form-group has-feedback item-readonly-container">
                                    <label for="item">{{ __('Text') }} <span class="text-danger">*</span></label>
                                    <div class="fw-bold" id="item_readonly"></div>
                                </div>
                                <div class="form-group has-feedback item-container">
                                    <label for="item">{{ __('Text') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="item" class="form-control" id="item" placeholder="classroom" value="" minlength="1" maxlength="255" required />
                                    <span class="fa fa-info form-control-feedback"></span>
                                    <span class="text-danger">{{ $errors->first('item') }}</span>
                                </div>
                                <div class="form-group has-feedback">
                                    <label for="text">{{ __('Translate As') }} <span class="text-danger">*</span></label>
                                    <textarea name="text" class="form-control" id="text" placeholder="ថ្នាក់រៀន" value="" rows="3" required></textarea>
                                    <span class="fa fa-info form-control-feedback"></span>
                                    <span class="text-danger">{{ $errors->first('text') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <x-backend.import-modal modalId="importModal" formAction="{{ route('translation.import') }}" sampleFileUrl="{{ asset('example/translation-import-sample.xlsx') }}"/>


</section>
@endsection
<!-- END PAGE CONTENT-->
@section('extraScript')
    <script type="text/javascript">
        $(document).ready(function () {
            var translations = @json($translations);
            Generic.initCommonPageJS();
            Generic.initDeleteDialog();
            // $('#localization-key').change(function(){
            //     var langKey = $(this).val();

            //     $('#localization-contents').text('');
            //     $('#localization-contents').val(translations[langKey]);
            // });
            $('#btn-submit').on('click',function(e){
                var jsonContents =  $('#localization-contents').val();
                try {
                    JSON.parse(jsonContents);
                    $('#error-info').text('');
                    $('#localization-form').submit();
                } catch (error) {
                    $('#error-info').text(error);
                    e.preventDefault();
                }
            });
        });
        $(document).on("click", "#btnAddNew", function(e){
            $(".item-readonly-container").addClass("d-none");
            $(".item-container").removeClass("d-none");
            $("#item_readonly").html($(this).attr("data-item"));
            $("#item").val('');
            $("#text").val('');
        });
        // $(document).on("change", "#search_locale", function(e){
        //     $(this).closest('form').submit();
        // });
        $(document).on("click", ".btn-edit-translate", function(e){
            $(".item-readonly-container").removeClass("d-none");
            $(".item-container").addClass("d-none");
            $("#item_readonly").html($(this).attr("data-item"));
            $("#item").val($(this).attr("data-item"));
            $("#text").val($(this).attr("data-text"));
        });
        $("#apply_filter").click(function(){
                var i = 0;
                $(".wrap_filter_form .select2").each(function(){
                    if ($(this).val()>0) {
                        $("#filterForm").append("<input type='hidden' value='"+$(this).val()+"' name='"+$(this).attr('name')+"'>");
                        i++;
                    }
                });
                if (i>0) $("#filterForm").submit();
            });

            $("#filters").click(function(){
                if ($(".wrap_filter_form").hasClass('d-none')) {
                    $(this).addClass('active');
                    $(".wrap_filter_form").removeClass('d-none');
                }else {
                    $(this).removeClass('active');
                    $(".wrap_filter_form").addClass('d-none');
                }
            });
    </script>
@endsection
