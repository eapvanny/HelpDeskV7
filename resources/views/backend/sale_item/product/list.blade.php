<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle') Product @endsection
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
            <li> {{ __('Sale Item') }} </li>
            <li class="active"> {{ __('Product') }} </li>
        </ol>
    </section>
    <!-- ./Section header -->
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="wrap-outter-header-title">
                    <h1>
                        {{ __('Product') }}
                        <small> {{ __('List') }} </small>
                    </h1>
                    <div class="box-tools pull-right">
                        @can('saleitem.product.store')
                            <a class="btn btn-info text-white" href="{{ URL::route('saleitem.product.create') }}">
                                <i class="fa fa-plus-circle"></i> {{ __('Add New') }}
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="wrap-outter-box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="datatabble" class="table table-bordered table-striped list_view_table display responsive no-wrap datatable-server" width="100%">
                                <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="10%"> {{ __('Code') }} </th>
                                    <th width="10%"> {{ __('Name') }} </th>
                                    <th width="10%"> {{ __('Name In Latin') }} </th>
                                    <th width="25%"> {{ __('Description') }} </th>
                                    <th width="25%"> {{ __('Fee Type') }} </th>
                                    <th width="15%"> {{ __('Status') }} </th>
                                    <th class="notexport" style="min-width: 120px;"> {{ __('Action') }} </th>
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

    </section>
    <!-- /.content -->
@endsection
<!-- END PAGE CONTENT-->

<!-- BEGIN PAGE JS-->
@section('extraScript')

    <script type="text/javascript">

        $(document).ready(function () {

            window.postUrl = '{{URL::Route("saleitem.product.status", 0)}}';
            window.changeExportColumnIndex = 5;
            window.excludeFilterComlumns = [0,1,6,7];
            Generic.initCommonPageJS();
            Generic.initDeleteDialog();
            window.filter_org = 1;
            Generic.initFilter();

            $('select[name="org_id"]').on('change', function () {
                let org_id = $(this).val();
                if (org_id.trim()) {
                    $('#frm_search').submit();
                }
            });

            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }

        });
        var t = $('#datatabble').DataTable({
            processing: false,
            serverSide: true,
            ajax:{
                url: "{!!  route('saleitem.product.index',request()->all()) !!}",
            },
            columns:[
                {
                    data: 'id',
                    name: 'id',
                },
                {
                    data: 'code',
                    name: 'code'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'name_latin',
                    name: 'name_latin'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'category',
                    name: 'category'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                },
            ],
            "fnDrawCallback": function() {
                $('#datatabble input.statusChange').bootstrapToggle({
                    on: "Active",
                    off: "Deactive"
                });
            }
        });

        // t.on( 'order.dt search.dt', function () {
        //     t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        //         cell.innerHTML = i+1;
        //     } );
        // } ).draw();

        $('#datatabble').delegate('.delete','click', function(e){
            let action = $(this).attr('href');
            console.log()
            $('#myAction').attr('action',action);
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
