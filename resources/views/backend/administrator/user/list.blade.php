<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle') System Admin @endsection
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
            <li> {{ __('Administrator') }} </li>
            <li class="active"> {{ __('System Admin') }} </li>
        </ol>
    </section>
    <!-- ./Section header -->
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="wrap-outter-header-title">
                    <h1>
                        {{ __('System Admin') }}
                        <small> {{ __('List') }} </small>
                    </h1>
                    <div class="box-tools pull-right">
                        <a class="btn btn-info text-white" href="{{ URL::route('administrator.user_create') }}"><i class="fa fa-plus-circle"></i> {{ __('Add New') }} </a>
                    </div>
                </div>
                <div class="wrap-outter-box">

                    <div class="box box-info">
                        <!-- /.box-header -->
                        <div class="box-body margin-top-20">
                            <div class="table-responsive">
                            <table id="listDataTable" class="table table-bordered table-striped list_view_table display responsive no-wrap" width="100%">
                                <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="20%"> {{ __('Name') }} </th>
                                    <th width="8%"> {{ __('Username') }} </th>
                                    <th width="25%"> {{ __('Email') }} </th>
                                    <th width="12%"> {{ __('Phone No') }}. </th>
                                    <th width="5%"> {{ __('Role') }} </th>
                                    <th width="5%"> {{ __('Status') }} </th>
                                    <th class="notexport" width="15%"> {{ __('Action') }} </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            {{$loop->iteration}}
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone_no }}</td>
                                        <td>{{ $user->role }}</td>
                                        {{-- <td>
                                            <!-- todo: have problem in mobile device -->
                                            <input class="statusChange" type="checkbox" data-pk="{{$user->id}}" @if($user->status) checked @endif data-toggle="toggle" data-on="<i class='fa fa-check-circle'></i>" data-off="<i class='fa fa-ban'></i>" data-onstyle="success" data-offstyle="danger">
                                        </td> --}}
                                        <td>@if($user->status == '0' || 0) {{ __('Inactive') }} @elseif ($user->status == '1' || 1) {{ __('Active') }} @endif</td>
                                        <td>
                                            <div class="change-action-item">
                                                <div class="btn-group">
                                                    <a title="Edit" href="{{URL::route('administrator.user_edit',$user->id)}}" class="btn btn-info text-white"><i class="fa fa-edit"></i></a>
                                                    </a>
                                                </div>
                                                <!-- todo: have problem in mobile device -->
                                                <div class="btn-group">
                                                    <form  class="myAction" method="POST" action="{{URL::route('administrator.user_destroy', $user->id)}}">
                                                        @csrf
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

    </section>
    <!-- /.content -->
@endsection
<!-- END PAGE CONTENT-->

<!-- BEGIN PAGE JS-->
@section('extraScript')
    <script type="text/javascript">
        $(document).ready(function () {
            window.postUrl = '{{URL::Route("administrator.user_status", 0)}}';
            window.changeExportColumnIndex = 6;
            Generic.initCommonPageJS();
            Generic.initDeleteDialog();
        });
    </script>
@endsection
<!-- END PAGE JS-->
