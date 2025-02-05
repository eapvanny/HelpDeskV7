<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle') Shifts @endsection
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
            <li> {{ __('Master Data') }} </li>
            <li class="active"> {{ __('Shifts') }} </li>
        </ol>
    </section>
    <!-- ./Section header -->
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">

            <div class="wrap-outter-header-title">
                <h1>
                    {{ __('Shifts') }}
                    <small> {{ __('List') }} </small>
                </h1>
                <div class="box-tools pull-right">
                <a class="btn btn-info text-white" href="{{ URL::route('shift.create') }}"><i class="fa fa-plus-circle"></i> {{ __('Add New') }} </a>
                </div>
            </div>
            <div class="wrap-outter-box">
                <div class="box box-info">
                        <!-- /.box-header -->
                        <div class="box-body margin-top-20">
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                                        <i class="fa-solid fa-file-import"></i> {{__('Import')}}
                                    </button>

                                </div>
                            </div>
                            <div class="table-responsive">
                            <table id="listDataTableWithSearch" class="table table-bordered table-striped list_view_table display responsive no-wrap" width="100%">
                                <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Name in Latin') }}</th>
                                    {{-- <th>{{ __('Monday') }}</th>
                                    <th>{{ __('Tuesday') }}</th>
                                    <th>{{ __('Wednesday') }}</th>
                                    <th>{{ __('Thursday') }}</th>
                                    <th>{{ __('Friday') }}</th>
                                    <th>{{ __('Saturday') }}</th>
                                    <th>{{ __('Sunday') }}</th> --}}
                                    <th class="notexport" width="10%"> {{ __('Action') }} </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($shifts as $shift)
                                    <tr>
                                        <td>{{ $shift->name }}</td>
                                        <td>{{ $shift->name_in_latin }}</td>
                                        {{-- <td>{!! ($shift->monday ?  '<i class="fa-regular fa-square-check"></i>' : '<i class="fa-regular fa-square"></i>') !!}</td>
                                        <td>{!! ($shift->tuesday ?  '<i class="fa-regular fa-square-check"></i>' : '<i class="fa-regular fa-square"></i>') !!}</td>
                                        <td>{!! ($shift->wednesday ?  '<i class="fa-regular fa-square-check"></i>' : '<i class="fa-regular fa-square"></i>') !!}</td>
                                        <td>{!! ($shift->thursday ?  '<i class="fa-regular fa-square-check"></i>' : '<i class="fa-regular fa-square"></i>') !!}</td>
                                        <td>{!! ($shift->friday ?  '<i class="fa-regular fa-square-check"></i>' : '<i class="fa-regular fa-square"></i>') !!}</td>
                                        <td>{!! ($shift->saturday ?  '<i class="fa-regular fa-square-check"></i>' : '<i class="fa-regular fa-square"></i>') !!}</td>
                                        <td>{!! ($shift->sunday ?  '<i class="fa-regular fa-square-check"></i>' : '<i class="fa-regular fa-square"></i>') !!}</td> --}}

                                        <td>
                                            <div class="change-action-item">
                                                <div class="btn-group">
                                                    <a title="Edit" href="{{URL::route('shift.edit', $shift->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                                </div>
                                                <!-- todo: have problem in mobile device -->
                                                <div class="btn-group d-none">
                                                    <form  class="myAction" method="POST" action="{{URL::route('shift.destroy', $shift->id)}}">
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
                        <!-- /.box-body -->
                    </div>
                </div>

            </div>
            </div>
        </div>
        <x-backend.import-modal modalId="importModal" formAction="{{ route('shift.import') }}" sampleFileUrl="{{ asset('example/shift-import-sample.xlsx') }}"/>


    </section>

    <style>
        @-moz-keyframes spin {
            from { -moz-transform: rotate(0deg); }
            to { -moz-transform: rotate(360deg); }
        }
        @-webkit-keyframes spin {
            from { -webkit-transform: rotate(0deg); }
            to { -webkit-transform: rotate(360deg); }
        }
        @keyframes spin {
            from {transform:rotate(0deg);}
            to {transform:rotate(360deg);}
        }
    </style>
    <!-- /.content -->
@endsection
<!-- END PAGE CONTENT-->

<!-- BEGIN PAGE JS-->
@section('extraScript')
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
