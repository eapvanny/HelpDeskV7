<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle') Role @endsection
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
            <li class="active"> {{ __('User') }} </li>
        </ol>
    </section>
    <!-- ./Section header -->
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
            <div class="wrap-outter-header-title">
                <h1>
                    {{ __('Role') }}
                    <small> {{ __('List') }} </small>
                </h1>
                <div class="box-tools pull-right">
                    <?php
                        $isFilter = false;
                        if ($org_id != '')
                        {
                            $isFilter = true;
                        }
                        ?>
                        <button id="filters"
                            class="btn btn-outline-secondary @if ($isFilter) active @endif">
                            <i class="fa-solid fa-filter"></i> {{ __('Filter') }}
                        </button>
                    <a class="btn btn-info text-white" href="{{ URL::route('user.role_create') }}"><i class="fa fa-plus-circle"></i> {{ __('Add New') }} </a>
                </div>
            </div>
            <div class="wrap-outter-box">
            <div class="box box-info">
                    <!-- /.box-header -->
                    <div class="box-body margin-top-20">
                        <div class="row">
                            <form action="{{ route('user.role_index') }}" method="GET" id="filterForm">

                            </form>
                            <div class="wrap_filter_form @if (!$isFilter) d-none @endif">
                                <button id="close_filter" class="btn btn-outline-secondary btn-sm">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                                <div class="row">
                                    <div class="col-3">
                                        @if($role_ref_id== AppHelper::USER_SUPER_ADMIN)
                                            {!! AppHelper::selectHeadOrg($errors, $org_id ?? 0) !!}
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mt-2">

                                        <button id="apply_filter"
                                            class="btn btn-outline-secondary btn-sm float-end">
                                            <i class="fa-solid fa-magnifying-glass"></i> {{ __('Apply') }}
                                        </button>
                                        <button id="cancel_filter" type="submit"
                                            class="btn btn-outline-secondary btn-sm float-end me-1">
                                            <i class="fa-solid fa-xmark"></i> {{ __('Cancel') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-3">
                            <table id="listDataTable" class="table table-bordered table-striped list_view_table display responsive no-wrap" width="100%">
                                <thead>
                                <tr>
                                    <th width="10%">#</th>
                                    <th width="60%"> {{ __('Name') }} </th>
                                    <th class="notexport" width="30%"> {{ __('Action') }} </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($roles as $role)
                                    <tr>
                                        <td>
                                            {{$loop->iteration}}
                                        </td>
                                        <td>{{ $role->name }}</td>
                                        <td>

                                            <!-- todo: have problem in mobile device -->

                                            @if(getAuthUser()->newRole->role_id === AppHelper::USER_SUPER_ADMIN)
                                                    <div class="btn-group">
                                                        <a title="Edit Permission" href="{{URL::route('user.role_update',$role->id)}}" class="btn btn-info text-white"><i class="fa fa-user-plus"></i></a>
                                                        </a>
                                                    </div>
                                            @elseif($role->ref_id != AppHelper::USER_SUPER_ADMIN
                                                && $role->ref_id != AppHelper::USER_SUPER_ADMIN_ORG)
                                                    <div class="btn-group">
                                                        <a title="Edit Permission" href="{{URL::route('user.role_update',$role->id)}}" class="btn btn-info text-white"><i class="fa fa-user-plus"></i></a>
                                                        </a>
                                                    </div>
                                            @endif
                                            @if($role->deletable)
                                                <div class="btn-group">
                                                    <form  class="myAction" method="POST" action="{{URL::route('user.role_destroy')}}">
                                                        @csrf
                                                        <input type="hidden" name="hiddenId" value="{{$role->id}}">
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                            <i class="fa fa-fw fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif



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

    </section>
    <!-- /.content -->
@endsection
<!-- END PAGE CONTENT-->

<!-- BEGIN PAGE JS-->
@section('extraScript')
    <script type="text/javascript">
        $(document).ready(function () {
            window.changeExportColumnIndex = -1;
            Generic.initCommonPageJS();
            Generic.initDeleteDialog();
            // $('#org_filter').change(function(){
            //     let org_id = $(this).val();
            //     let urlLastPart ="?org_id="+org_id;
            //     let getUrl = window.location.href.split('?')[0]+urlLastPart;
            //     window.location = getUrl;
            // });

            $("#apply_filter").click(function(){
            $("#filterForm input[type='hidden']").remove();
            $(".wrap_filter_form .select2").each(function(){
                var selectElement = $(this);
                if (selectElement.val() > 0) {
                    $("#filterForm").append("<input type='hidden' value='"+selectElement.val()+"' name='"+selectElement.attr('name')+"'>");
            selectElement.prop('disabled', true);
            }
            });

             $("#filterForm").submit();
            });

            // You might also need a logic to re-enable the select elements if the user cancels the filter or modifies their choices
            $("#cancel_filter, #close_filter").click(function(){
                // Re-enable any disabled select elements
                $(".wrap_filter_form .select2").prop('disabled', false);

                // Optional: Clear any dynamically added hidden inputs if necessary
                $("#filterForm input[type='hidden']").remove();
            });

            // Ensure to re-enable select elements before a page refresh or navigating away to preserve user selections
            $(window).on('beforeunload', function(){
                $(".wrap_filter_form .select2").prop('disabled', false);
            });


            // end
            $("#cancel_filter").click(function(e){
                e.preventDefault();
                window.location.href = "{{ route('user.role_index') }}";
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
            $('#close_filter').click(function(e){
                $("#filters").trigger('click');
                e.preventDefault();
            });
        });
    </script>
@endsection
<!-- END PAGE JS-->
