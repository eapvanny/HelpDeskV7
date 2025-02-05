
<div class="box-header with-border ps-0 ">
    <h3 class="box-title"> {{ __('Students Info') }} </h3>
    <div class="box-tools pull-right">
        <a class="btn btn-info text-white" id="btn-add-student" data-bs-toggle="modal" data-bs-target="#student-records"><i class="fa fa-plus-circle"></i> {{ __('Add New') }} </a>
    </div>
</div>
<table id="listDataTableWithSearch" class="table table-bordered table-striped list_view_table display responsive no-wrap" width="100%">
    <thead>
        <tr>
            {{-- <th class="text-center">Date</th>
            <th class="text-center">Status</th> --}}
            <th width="5%">#</th>
            <th class="notexport" width="7%"> {{ __('Photo') }} </th>
            <th width="10%"> {{ __('ID Card') }} </th>
            <th width="15%"> {{ __('Name') }} </th>
            <th width="15%"> {{ __('Name In Latin') }} </th>
            <th width="10%"> {{ __('Phone No') }} </th>
            <th class="notexport" width="15%"> {{ __('Action') }} </th>
        </tr>
    </thead>
    <tbody>
        @foreach($parent->student as $student)
            @php
                $full_path = null;
                $regi_no = null;
                $roll_no = null;
                $card_no = null;
            $registration = $student->registration()
                                        ->orderBy('id', 'desc')
                                        ->first();

                if($student && !empty($student->photo)){
                    $full_path = Storage::url($student->photo);
                }
                if($registration){
                $regi_no = $registration->board_regi_no;
                $roll_no = $registration->roll_no;
                $card_no = $registration->card_no;
                }

                

            @endphp
            <tr>
                <td>
                    {{$loop->iteration}}
                </td>
                <td>
                    @if ($full_path)
                        <img class="img-responsive center" style="height: 35px; width: 35px;" src="{{$full_path}}" alt="">
                    @else
                        <img class="img-responsive center" style="height: 35px; width: 35px;" src="{{ asset('images/avatar.jpg')}}" alt="">
                    @endif

                </td>
                <td>{{ $student->id_card_no }}</td>
                <td>{{ $student->name.' '.$student->family_name }}</td>
                <td>{{ $student->name_in_latin.' '.$student->family_name_in_latin }}</td>
                <td>{{ $student->phone_no }}</td>
                <td>
                    <div class="btn-group">
                        <span class="change-action-item"><a title="Show"  href="{{URL::route('student.show',$student->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a></span>
                    </div>
                    <div class="btn-group">
                        <form action="{{ route('parent.student.delete') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <span type="submit" class="change-action-item"><a class="btn btn-danger btn-sm delete unlinke-parent-student" id="unlinke-parent-student" title="Delete"><i class="fa fa-fw fa-trash"></i></a></span>
                            {{-- <button type="submit" class="btn btn-danger btn-sm delete unlinke-parent-student"  id="unlinke-parent-student" title="Delete"><i class="fa fa-fw fa-trash"></i></button> --}}
                            <input type="hidden" name="data-parentId" value="{{$parent->id}}">
                            <input type="hidden" name="data-studentID" value="{{$student->id}}">
                        </form>
                    </div>

                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="modal modal-lg fade" id="student-records" tabindex="-1" aria-labelledby="student-records" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content overflow-y-scroll">
            <div class="modal-header">
                <h5 class="modal-title fs-3 text-secondary">{{ __('Students List') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="datatable-student" class="table table-bordered table-striped list_view_table display responsive no-wrap datatable-server" width="100%">
                    <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="5%"> {{ __('Photo') }} </th>
                        <th width="10%"> {{ __('Name') }} </th>
                        <th width="10%"> {{ __('Name In Latin') }} </th>
                        <th width="10%"> {{ __('Gender') }} </th>
                        <th width="7%"> {{ __('Phone No') }} </th>
                        <th width="5%"> {{ __('Action') }} </th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>

                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-add-students">Add</button> 
            </div>
        </div>
    </div>
</div>
