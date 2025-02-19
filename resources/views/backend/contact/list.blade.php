<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle')
    Support
@endsection
<!-- End block -->

@section('extraStyle')
    <style>
        
    </style>
@endsection

<!-- Page body extra class -->
@section('bodyCssClass')
@endsection
<!-- End block -->

<!-- BEGIN PAGE CONTENT-->
@section('pageContent')
    <!-- Section header -->
    <section class="content-header">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ __('Dashboard') }} </a></li>
            <li class="active"> {{ __('Supporter') }} </li>
        </ol>
    </section>
    <!-- ./Section header -->
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="wrap-outter-header-title">
                    <h1>
                        {{ __('Supporter') }}
                        <small> {{ __('List') }} </small>
                    </h1>
                    <div class="box-tools pull-right">
                        <a class="btn btn-info text-white" href="{{ URL::route('contact.create') }}"><i
                                class="fa fa-plus-circle"></i> {{ __('Add New') }} </a>
                    </div>
                </div>

                <div class="wrap-outter-box">
                    <div class="box box-info">
                        <!-- /.box-header -->
                        <div class="box-body margin-top-20">
                            <div class="table-responsive mt-4">
                                <table id="datatabble"
                                    class="table table-bordered table-striped list_view_table display responsive no-wrap datatable-server"
                                    width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th> {{ __('Photo') }} </th>
                                            <th> {{ __('ID No.') }} </th>
                                            <th> {{ __('Name') }} </th>
                                            <th> {{ __('Name in Latin') }} </th>
                                            <th class="notexport"> {{ __('Action') }} </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="btn-group">
                            <form id="myAction" method="POST">
                                @csrf
                                <input name="_method" type="hidden" value="DELETE">
                            </form>
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
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            t = $('#datatabble').DataTable({
                processing: false,
                serverSide: true,
                ajax: {
                    url: "{!! route('contact.index', request()->all()) !!}",
                },
                pageLength: 10,
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'photo',
                        name: 'photo'
                    },
                    {
                        data: 'id_card',
                        name: 'id_card'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'name_in_latin',
                        name: 'name_in_latin'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    }
                ],
            });
            //delete grade_level
            $('#datatabble').delegate('.delete', 'click', function(e) {
                let action = $(this).attr('href');
                console.log()
                $('#myAction').attr('action', action);
                e.preventDefault();
                swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this record!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dd4848',
                    cancelButtonColor: '#8f8f8f',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it'
                }).then((result) => {
                    if (result.value) {
                        $('#myAction').submit();
                    }
                });
            });
        });
    </script>
@endsection
<!-- END PAGE JS-->
