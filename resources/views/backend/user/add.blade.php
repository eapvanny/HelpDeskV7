<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle') User @endsection
<!-- End block -->

<!-- Page body extra class -->
@section('bodyCssClass') @endsection
<!-- End block -->
@section('extraStyle')
    <style>
        fieldset .form-group{
            margin-bottom: 0px;
        }
        fieldset .iradio .error,fieldset .icheck .error{
            display: none !important;
        }

        @media (max-width: 600px) {
            .display-flex{
                display: inline-flex;
            }
        }
        @media (max-width: 768px) {
            .display-flex{
                display: inline-flex;
            }
        }
        .checkbox, .radio{
            display: inline-block;
        }
        .checkbox{
            margin-left: 10px;
        }
        legend {
            margin: 0;
            width: unset;
            font-weight: 700;
            font-size: 14px;
            color: #0059a1;
            display: block;
            padding-inline-start: 2px;
            padding-inline-end: 2px;
            border-width: initial;
            border-style: none;
            border-color: initial;
            border-image: initial;
        }
        fieldset {
            padding: 1em 0.625em 1em;
            border: 1px solid #9a9a9a;
            margin: 2px 2px;
            padding: .35em .625em .75em;
            margin-top: 4px;
        }
    </style>
@endsection
<!-- BEGIN PAGE CONTENT-->
@section('pageContent')
    <!-- Section header -->
    <section class="content-header">
        <ol class="breadcrumb">
            <li><a href="{{URL::route('user.dashboard')}}"><i class="fa fa-dashboard"></i> {{ __('Dashboard') }} </a></li>
            <li> {{ __('Administrator') }} </li>
            <li><a href="{{URL::route('user.index')}}"> {{ __('User') }} </a></li>
            <li class="active">@if($user) Update @else {{ __('Add') }} @endif</li>
        </ol>
    </section>
    <!-- ./Section header -->
    <!-- Main content -->
    <section class="content">
                
    <form novalidate id="entryForm" action="@if($user) {{URL::Route('user.update', $user->id)}} @else {{URL::Route('user.store')}} @endif" method="post" enctype="multipart/form-data" autocomplete="off">
        <div class="row">
            <div class="col-md-12">
                <div class="wrap-outter-header-title">
                    <h1>
                        {{ __('User') }}
                        <small>@if($user) Update @else {{ __('Add New') }} @endif</small>
                    </h1>

                    <div class="box-tools pull-right">
                        <a href="{{URL::route('user.index')}}" class="btn btn-default">Cancel</a>
                        <button type="submit" class="btn btn-info pull-right text-white"><i class="fa @if($user) fa-refresh @else fa-plus-circle @endif"></i> @if($user) Update @else Add @endif</button>
                    </div>
                </div>
            </div>
        </div>    
        <div class="wrap-outter-box">
            <input id="org_detail" type="hidden" name="org_detail" value="">
            <div class="box-header">
                <div class="callout callout-danger">
                    <p><b> {{ __('Note') }}:</b> {{ __('Create a role before create user if not exist') }} .</p>
                </div>
            </div>
            <div class="box-body">
                @csrf
                @if($user)  {{ method_field('PATCH') }} @endif

                <!-- End organization -->
                <div class="row">
                    <div class="col-md-6 col-xl-3">
                        <div class="form-group has-feedback">
                            <label for="status"> {{ __('Status') }} <span class="text-danger">*</span></label>
                            <select name="status" class="form-select bg-light" id="status">
                                <option value="1" {{ old('status', optional($user)->status) == 1 || is_null($user) ? 'selected' : '' }}> {{ __('Active') }} </option>
                                <option value="0" {{ old('status', optional($user)->status) == 0 && !is_null($user) ? 'selected' : '' }}> {{ __('Inactive') }} </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label for="name"> {{ __('Name') }} <span class="text-danger">*</span></label>
                            <input autofocus type="text" class="form-control" name="name" placeholder="name" value="@if($user){{ $user->name }}@else{{old('name')}}@endif" required minlength="2" maxlength="255">
                            <span class="fa fa-info form-control-feedback"></span>
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label for="email"> {{ __('Email') }} <span class="text-danger">*</span></label>
                            <input  type="email" class="form-control" name="email"  placeholder="email address" value="@if($user){{$user->email}}@else{{old('email')}}@endif" maxlength="100" required autocomplete="new-password">
                            <span class="fa fa-envelope form-control-feedback"></span>
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label for="phone_no"> {{ __('Phone/Mobile No') }}.</label>
                            <input  type="text" class="form-control" name="phone_no" placeholder="phone or mobile number" value="@if($user){{$user->phone_no}}@else{{old('phone_no')}}@endif" maxlength="15">
                            <span class="fa fa-phone form-control-feedback"></span>
                            <span class="text-danger">{{ $errors->first('phone_no') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label for="role_id"> {{ __('User Role') }}
                                <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Set a user role"></i>
                                <span class="text-danger">*</span>
                            </label>
                            {!! Form::select('role_id', $roles, $role_id , ['class' => 'form-control select2', 'required' => 'true',$role_id ? 'readonly' : '']) !!}
                            <span class="form-control-feedback"></span>
                            <span class="text-danger">{{ $errors->first('role_id') }}</span>
                        </div>
                    </div>
                </div>
            {{-- @if(!$user) --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            <label for="username"> {{ __('Username') }} <span class="text-danger">*</span></label>
                            <input  type="text" class="form-control" value="@if($user){{$user->username}}@else{{old('username')}}@endif" name="username" required minlength="5" maxlength="255" autocomplete="new-password">
                            <span class="glyphicon glyphicon-info-sign form-control-feedback"></span>
                            <span class="text-danger">{{ $errors->first('username') }}</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="photo"> {{ __('Photo') }} <br/><span class="text-muted fst-italic">(Files: jpeg, jpg, or png, min dimension: 50 x 50 pixel, 2Mb max size)</span></label>
                                        <input  type="file" class="form-control" accept=".jpeg, .jpg, .png" name="photo" placeholder="{{ __('Photo image') }}">
                                        <span class="glyphicon glyphicon-open-file form-control-feedback" style="top:45px;"></span>
                                        <span class="text-danger">{{ $errors->first('photo') }}</span>
                                    </div>
                                    <div class="col-md-2">
                                        @if($user && isset($user->photo))
                                            <img src="{{ Storage::url($user->photo) }}" alt="Current Photo" style="max-height: 50px; margin-top: 40px">
                                            <input type="hidden" name="oldPhoto" value="{{$user->photo}}">
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            @if(!$user)
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            <label for="password"> {{ __('Password') }} <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" placeholder="Password" required minlength="6" maxlength="50" autocomplete="new-password">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        </div>
                    </div>
                </div>
            @endif
                @if ($ref_id && $ref_id !== AppHelper::USER_STUDENT && $ref_id !== AppHelper::USER_PARENTS)
                    @if ($user && count($organizations) > 1)
                    <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading"><i class="fa fa-info-circle"></i> &nbsp;&nbsp;&nbsp;{{ __('Detail') }} </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group has-feedback">
                                                <label for="teacher_id"> {{ __('Organizations Name') }}
                                                    <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Set organizations"></i>
                                                </label>
                                                {!! Form::select('organizations_id', $organizations, null, ['id' => 'sl-organizations','placeholder' => 'Pick a organizations...','class' => 'form-control select2']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-2 mt-5">
                                            <a id="btn_add_detail" class="btn btn-outline-secondary btn-sm"><i class="fa fa-plus"></i> &nbsp;&nbsp;&nbsp;{{ __('Add Detail') }} </a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-10"></div>

                                    </div>
                                    <div class="row" style="padding-top : 10px;">
                                        {{-- <input type="hidden" id="strDetail" @if($user_org) value="{{json_encode($user_org)}}" @else value="{{json_encode([])}}"  @endif> --}}
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table id="listDataTableOrgDetail" class="table table-bordered table-striped list_view_table display responsive no-wrap" width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th width="5%">#</th>
                                                        <th width="25%"> {{ __('Organizations') }} </th>
                                                        <th class="notexport" width="10%"> {{ __('Action') }} </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        <input type="hidden" id="strDetail" @if(count($user_org) > 0) value="{{json_encode($user_org)}}" @else value="{{json_encode([])}}"  @endif>
                                                        @if(count($user_org) > 0)
                                                            {{-- @dd($user_org); --}}
                                                            @foreach ($user_org as $organization)
                                                                <tr>
                                                                    <td>
                                                                        {{$organization->organizations_id}}
                                                                    </td>
                                                                    <td>{{ $organization->organizations_name}}</td>
                                                                    <td>
                                                                        <div class="btn-group">
                                                                            <a id="btnDelete" data-organization="{{$organization->organizations_name}}" class="btn btn-danger btn-sm" title="Delete">
                                                                                <i class="fa fa-fw fa-trash"></i>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                    {{-- <tfoot>
                                                        <tr>
                                                            <th width="5%">#</th>
                                                            <th width="25%"> {{ __('Organizations') }} </th>
                                                            <th class="notexport" width="10%"> {{ __('Action') }} </th>
                                                        </tr>
                                                    </tfoot> --}}
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
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
            Generic.initCommonPageJS();

            @if ($ref_id !== AppHelper::USER_STUDENT && $ref_id !== AppHelper::USER_PARENTS)

                @if ($user && count($organizations) > 1)
                    // Globle variable
                    var $body = $("body"),$data_organizations_detail = [],$index = 0,$key = 0;
                    // *** SET OLD SUJECT DETAIL ***

                    var $old_organizations_detail = JSON.parse($("#strDetail").val());
                    console.log($old_organizations_detail);
                    if( ! $.isEmptyObject($old_organizations_detail)){
                        console.log('old organizations detail : ',$old_organizations_detail);
                            if($old_organizations_detail.length > 0){
                            var  $count = $old_organizations_detail.length;
                                for (let i = 0; i < $count; i++) {
                                    $data_organizations_detail.push({
                                        organization : $old_organizations_detail[i].organizations_name ,
                                        organizationID : $old_organizations_detail[i].organizations_id,
                                    });
                                }
                            }
                    } else {
                        console.log("No old data organizations detail");
                    }

                    console.log('old data organizations detail : ',$data_organizations_detail);
                    var $table = $('#listDataTableOrgDetail').DataTable( {
                        pageLength: 25,
                        lengthChange: false,
                        orderCellsTop: true,
                        responsive: true,
                        "columnDefs": [ {
                            "searchable": false,
                            "orderable": false,
                            "targets": 0
                        } ],
                        "order": [[ 1, 'asc' ]]
                    } );

                    $body.on("click","#btn_add_detail",function(){
                        var $flag = false, $tr = "",
                            $organization = $('select[name="organizations_id"]').find(":selected");

                        if($.trim($organization.text()).toLocaleLowerCase() == "pick a organizations...")
                        {
                            swal("error", "Select organizations before add :)", "error");
                            return false;
                        }

                        if($data_organizations_detail){
                            $.each($data_organizations_detail,function(i,$sub){
                                if($sub.organization == $organization.text()){
                                    swal("error", "Existing :)", "error");
                                    $flag = true;
                                    return false;
                                }
                            });
                        }
                        if($flag){
                            return false;
                        }

                        $data_organizations_detail.push({
                            organization : $organization.text(),
                            organizationID : $organization.val()
                        });
                        var $action = `<div class="btn-group">
                                            <a id="btnDelete" data-organization="${$organization.text()}" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fa fa-fw fa-trash"></i>
                                            </a>
                                        </div>`;

                        $table.row.add([ $organization.val(),$organization.text(),$action]).draw();
                        // $table.on( 'order.dt search.dt', function () {
                        //     $table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                        //         cell.innerHTML = i+1;
                        //     } );
                        // } ).draw();
                    });

                    $body.on("click","#btnDelete",function(){
                        var $organization = $(this).data("organization");

                        if($data_organizations_detail){
                            $.each($data_organizations_detail,function(i,$sub){
                                if($sub.organization == $organization){
                                    $data_organizations_detail.splice(i,1);
                                    return false;
                                }
                            });
                        }
                        $table.row( $(this).parents('tr') ).remove().draw();
                    });

                    $('#listDataTableSubDetail tbody').on( 'click', 'tr', function () {
                        $index = $table.row( this ).index();
                    } );

                    $body.on("submit","form",function(){
                        if($.isEmptyObject($data_organizations_detail)){
                            swal("error","Please, Add detail minimum 1 item.","error");
                            return false;
                        }
                        $("#org_detail").val(JSON.stringify($data_organizations_detail));
                    });
                @endif
            @endif


        });
    </script>
@endsection
<!-- END PAGE JS-->
