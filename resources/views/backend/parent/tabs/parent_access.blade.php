<div class="box-header with-border ps-0 ">
    <h3 class="box-title">{{ __('parents Access') }}</h3>
    <div class="box-tools pull-right">
        @if ($parent->user_id !== NULL)
            <a class="btn btn-info text-white" id="btn-add-parent" href="{{route('p_change_passwords',['id'=>$parent->user_id])}}" ><i class="fa fa-refresh"></i> {{ __('change Password') }} </a>
        @else
            <a class="btn btn-info text-white" id="btn-add-parent" href="{{URL::route('parents.edit',$parent->id)}}" ><i class="fa fa-plus-circle"></i> {{ __('Create User') }} </a>
        @endif
        
    </div>
</div>
@if ($parent->user_id === NULL)
    <div class="row">
        <div class="col-lg-9 col-sm-6">
            <span>{{ __('No user assigned!, Please create user') }}</span>
        </div>
    </div>
@else
<div class="row">
    <div class="col-lg-3 col-sm-6">
        <label for="" class="text-secondary">{{ __('Email')}}:</label>
    </div>
    <div class="col-lg-9 col-sm-6">
        <p>{{ optional($parent)->email }}</p>
    </div>
    <div class="col-lg-3 col-sm-6">
        <label for="" class="text-secondary">{{ __('Username')}}:</label>
    </div>
    <div class="col-lg-9 col-sm-6">
        <p>{{ optional($parent->user)->username }}</p>
    </div>
    <div class="col-lg-3 col-sm-6">
        <label for="" class="text-secondary">{{ __('Username')}}:</label>
    </div>
    <div class="col-lg-9 col-sm-6">
        <p>{{ __('**************') }}</p>
    </div>
</div>
@endif
