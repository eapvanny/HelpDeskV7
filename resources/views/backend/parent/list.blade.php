<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle') Parent @endsection
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
            <li> {{ __('Student Management') }} </li>
            <li class="active"> {{ __('Parent') }} </li>
        </ol>
    </section>
    <!-- ./Section header -->
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="wrap-outter-header-title">
                    <h1>
                        {{ __('Parent') }}
                        <small> {{ __('List') }} </small>
                    </h1>
                    <div class="box-tools pull-right">
                        <button class="btn btn-info text-white" id="btnAdd" data-bs-toggle="modal"data-bs-target="#modal-search-student">  
                            <i class="fa-solid fa-magnifying-glass"></i> {{ __('Search Parents') }}
                        </button>
                        @can('parents.create')
                        <a class="btn btn-info text-white" href="{{ URL::route('parents.create') }}"><i class="fa fa-plus-circle"></i> {{ __('Add New') }} </a>
                        @endcan 
                    </div>
                </div>
                <div class="wrap-outter-box">
                    <div class="box box-info">
                        <div class="box-header d-none">
                            <div class="row">
                                <form method="get" id="frm_search">
                                    <div class="row">
                                        {!! AppHelper::selectOrgOfUser($selected_org) !!}
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body margin-top:20px;">
                            <div class="table-responsive">
                            <table id="datatabble" class="table table-bordered table-striped list_view_table display responsive no-wrap datatable-server" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th class="notexport" width="10%"> {{ __('Photo') }} </th>
                                        <th width="15%"> {{ __('Name') }} </th>
                                        <th width="15%"> {{ __('Name In Latin') }} </th>
                                        <th width="15%"> {{ __('Phone No') }} </th>
                                        <th width="10%"> {{ __('Type') }} </th>
                                        <th class="notexport" width="10%"> {{ __('Action') }} </th>
                                    </tr>
                                    </thead>
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

<div class="modal modal-lg fade" id="modal-search-student" tabindex="-1" aria-labelledby="addStudentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded">
            <div class="modal-header py-0 m-3">
                <div class="box-title">
                    <h5>
                        {{ __('Find Parents for all Organizations') }}
                    </h5>
                </div>
                <button type="button" class="btn-close" id="top-close-student-search" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0 ">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group has-feedback">
                            <label for="name"> {{ __('Family Name') }} <span class="text-danger">*</span></label>
                            <input autofocus type="text" class="form-control" name="family_name" id="family_name" placeholder="" value="" required minlength="1" maxlength="255" required>
                            <span class="fa fa-info form-control-feedback"></span>
                            <span class="text-danger">{{ $errors->first('family_name') }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group has-feedback">
                            <label for="name"> {{ __('Name') }} <span class="text-danger">*</span></label>
                            <input autofocus type="text" class="form-control" name="name" id="name" placeholder="" value="" required minlength="1" maxlength="255" required>
                            <span class="fa fa-info form-control-feedback"></span>
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group has-feedback float-start pt-4">
                            <label for=""></label>
                            <button  type="button" id="btn-student-search" class="btn btn-info next text-white mt-2 btn-sm"><i class="fa-solid fa-magnifying-glass me-2"></i>{{__('Search')}}</button>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="box box-info">
                    <div class="table-responsive box-body margin-top-20">
                        <table id="datatable" class="table table-bordered table-striped list_view_table display responsive no-wrap datatable-server d-none" width="100%">
                            <thead>
                                <tr>
                                    <th width="3%">{{__('Photo')}}</th>
                                    <th width="15%">{{__('Full Name')}}</th>
                                    <th width="15%">{{__('Name In Latin')}}</th>
                                    <th width="5%">{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
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
            window.postUrl = '{{URL::Route("parents.status", 0)}}';
            window.changeExportColumnIndex = 5;
            window.excludeFilterComlumns = [0,2,3,6,7];
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
                bLengthChange: false,
                ajax:{
                    url: "{!! route('parents.index',Request::query()) !!}",
                },
                columns:[
                    {
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: 'photo',
                        name: 'photo',
                        orderable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'name_latin',
                        name: 'name_latin',
                    },
                    {
                        data: 'phone',
                        name: 'phone',
                    },
                    {
                        data: 'type',
                        name: 'type',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    }
                ],
                "fnDrawCallback": function() {
                    $('#datatabble input.statusChange').bootstrapToggle({
                        on: "<i class='fa fa-check-circle'></i>",
                        off: "<i class='fa fa-ban'></i>"
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


            //check new student or student search thier information
			$('input[name="i_checker_std_reg"]').on('ifChanged', function(event){
				if($('#i_checker_new_std').is(':checked')){
					$('.popup-box').animate({
						opacity: 0,
						marginTop: "100px",
					}, 500, function(){
						$('.popup-box').addClass('d-none');
					});
					$('#id_section_search').removeClass('d-none');
				}
				if($('#i_checker_search_std').is(':checked')){
					$('#modal-search-student').modal('show');
				}else{
					$('#modal-search-student').modal('hide');
				}
			});

			// Uncheck the checkbox
			$('#modal-search-student').on('hidden.bs.modal', function (e) {
				$('input[name="i_checker_std_reg"]').parent().removeClass('checked');
				$('input[name="i_checker_std_reg"]').prop('checked', false);
			});

            //search student
			$('#btn-student-search').click(function(e){
				var name = $('#name').val();
				var family_name = $('#family_name').val();
				$('#datatable').DataTable({
					destroy: true,
					processing: false,
					serverSide: true,
					bLengthChange: false,
					searching: false,
					bPaginate: false,
					bInfo: false,
					ajax: {
						method: 'GET',
						url: "{!! route('parent.search', request()->all()) !!}",
						data: {
							name : name,
							family_name : family_name,
						},
					},
					columns: [
							{
								data: 'photo',
								name: 'photo',
								orderable: false
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

					fnDrawCallback: function() {
						$(this).removeClass('d-none');
                	}
            	});
			});
            $(document).on('click', '.btn-add-parent', function() {
                var parentIds = $(this).data('parentid');
                $.ajax({
                    type:'POST',
                    url : "{!! route('parent.org.create') !!}",
                    _token: '{{ csrf_token() }}',
                    data:{
                        parent_id : parentIds,
                    },
                    success: function(response){
                        if(response.message){
                            swal({
                                    text: response.message,
                                    type: "success",
                                    timer: 2000
                                });
                            // $('#datatable').DataTable().ajax.reload();
                            location.reload();
                        } else {
                            swal({
                                    title: 'Something went wrong',
                                    text: response.error,
                                    type: "warning",
                                    timer: 2000
                                });
                        }
                    },
                    error: function(error){
                        $('#btn-add-parent').prop('disabled', false);
                        swal({
                            title: 'Something went wrong',
                            text: error,
                            type: "warning",
                            timer: 2000
                        });
                    }
                });
            });  

        });
    </script>
@endsection
<!-- END PAGE JS-->
