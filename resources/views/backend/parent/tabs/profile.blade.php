
    <div class="box-header with-border ps-0 @isset($hide_header){{ 'd-none' }}@endisset">
        <h3 class="box-title"> {{ __('Parent Info') }} </h3>
    </div>

    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <label for="" class="text-secondary" > {{ __('Family Name')}} :</label>
        </div>
        <div class="col-lg-3 col-sm-6">
                <p>{{$parent->family_name ?? 'N/A'}}</p>
        </div>
        <div class="col-lg-3 col-sm-6">
            <label for="" class="text-secondary"> {{ __('Name')}} :</label>
        </div>
        <div class="col-lg-3 col-sm-6">
            <p>{{$parent->name ?? 'N/A'}}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <label for="" class="text-secondary" > {{ __('Family Name In Latin')}} :</label>
        </div>
        <div class="col-lg-3 col-sm-6">
                <p>{{$parent->family_name_latin ?? 'N/A'}}</p>
        </div>
        <div class="col-lg-3 col-sm-6">
            <label for="" class="text-secondary"> {{ __('Name In Latin')}} :</label>
        </div>
        <div class="col-lg-3 col-sm-6">
            <p>{{$parent->name_in_latin ?? 'N/A'}}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <label for=""class="text-secondary"> {{ __('ID Card')}} :</label>
        </div>
        <div class="col-lg-3 col-sm-6">
            <p>{{$parent->id_card ?? 'N/A'}}</p>
        </div>
        <div class="col-lg-3 col-sm-6">
            <label for=""class="text-secondary"> {{ __('Phone No')}} :</label>
        </div>
        <div class="col-lg-3 col-sm-6">
            <p for="">{{$parent->phone_no ?? 'N/A'}}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <label for=""class="text-secondary"> {{ __('Nationality')}} :</label>
        </div>
        <div class="col-lg-3 col-sm-6">
            <p for="">{{$parent->nationality ?? 'N/A'}}</p>
        </div>
        <div class="col-lg-3 col-sm-6">
            <label for=""class="text-secondary"> {{ __('Occupation')}} :</label>
        </div>
        <div class="col-lg-3 col-sm-6">
            <p for="">{{$parent->occupation ?? 'N/A'}}</p>
        </div>
    </div>
    <div class="row">

        <div class="col-lg-3 col-sm-6">
            <label for=""class="text-secondary"> {{ __('Occupation Address')}} :</label>
        </div>
        <div class="col-lg-3 col-sm-6">
            <p for="">{{$parent->company_city ?? 'N/A'}}</p>
        </div>
    </div>


    {{-- @isset($hide_header){!! '<div class="d-none">' !!}@endisset
    <p class="text-info" style="font-size: 16px;border-bottom: 1px solid #eee;"> {{ __("CONSENT TO USE CHILD’S PHOTOGRAPHS & IMAGES Info") }} :</p>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <label for=""class="text-secondary">{{ __("I hereby the consent to the School’s using my child’s photographs, video and images in any/all of the School’s promotional materials including printed ads, video, website and any other promotional materials.")}}
            </label>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-sm-12">
            <label for=""class="text-secondary"> {{ __('CONSENT TO USE CHILD’S IMAGES') }} :</label>
        </div>
        <div class="col-lg-6 col-sm-12">
            <p for="">{{$parent->consent_to_user_child_images == 0?'NO':'YES'}}</p>
        </div>
    </div>
    @isset($hide_header){!! '</div>' !!}@endisset --}}
