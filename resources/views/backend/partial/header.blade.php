@php
    use Illuminate\Support\Facades\Storage;

    $authUser = Auth::user();
    $photoPath = $authUser->photo ? asset('storage/' . $authUser->photo) : asset('images/avatar.png');
@endphp
<header class="main-header shadow-sm">
    <!-- Logo -->
    <a href="{{ URL::route('dashboard.index') }}" class="logo hidden-xs logo-hitech">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">
            <img src="{{ asset('images/Background1.jpg') }}" alt="logo-mini" style="border-radius: 50%; margin-top: 14px">
        </span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg" style="width: 60%; height:100%; margin: auto">
            <img src="{{ asset('images/Hi-Tech_Water_Logo.png') }}" alt="logo-md">
        </span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-expand-lg p-0 inline-flex justify-content-between">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle text-decoration-none fas " data-toggle="push-menu" role="button">

        </a>
        {{-- @if ( $showOrgTop )
            @if ( isset($organizations_filter_list) )
                <div class="col-md-4">
                    <select class="form-control select_org_filter organization_filter" required="false" name="organization_filter">

                        @foreach ($organizations_filter_list as $org_filter)

                            <option value="{{$org_filter->id}}" @if(isset($organizations_filter_selected_id) && $org_filter->id==$organizations_filter_selected_id) {{ 'selected="selected"' }} @endif>
                                @if (app()->currentLocale()=="kh")
                                    {{optional($org_filter)->name}}
                                @else
                                    {{optional($org_filter)->name_in_latin}}
                                @endif
                                @if ($org_filter->short_name!="")
                                    ({{optional($org_filter)->short_name}})
                                @endif
                            </option>

                        @endforeach
                    </select>
                </div>
            @endif
        @endif --}}
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Site Start -->
                {{-- @if ($frontend_website)
                    <li class="dropdown site-menu mx-2">
                        <a target="_blank" title="Site" href="{{ URL::route('home') }}" class="dropdown-toggle"
                            data-toggle="tooltip" title="" data-placement="bottom"
                            data-original-title="Visit Site">
                            <i class="fa fa-globe"></i>
                        </a>
                    </li>
                @endif --}}
                <!-- Site Close -->
                <!-- Notifications: style can be found in dropdown.less-->
                {{-- <li class="dropdown messages-menu mx-2">
                    <a href="#" class="dropdown-toggle text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false"    >
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-danger"><lable class="alert-image notification_badge">0</lable></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header notificaton_header">You have 0 recent notifications</li>
                        <li>
                            <ul class="menu notification_top"></ul>
                        </li>
                        <li class="footer"><a href="{{route('user.notification_unread')}}">See All Notifications</a></li>
                    </ul>
                </li> --}}
                {{-- @if ($show_language)
                <li class="dropdown lang-menu mx-2">
                    <a href="#" class="dropdown-toggle text-decoration-none" data-bs-toggle="dropdown">
                        <img class="language-img" src="{{ asset('images/lang/'.$locale.'.png') }}">
                        <span class="label label-warning">2</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"> Language</li>
                        @foreach ($languages as $key => $lang)
                            <li class="language" id="bangla">
                                <a href="#">
                                    <div class="pull-left">
                                        <img src="{{ asset('images/lang/'.$key.'.png') }}">
                                    </div>
                                    <h4>
                                        {{$lang}} @if ($locale == $key) <i class="glyphicon glyphicon-ok green pull-right"></i> @endif
                                    </h4>
                                </a>
                            </li>
                        @endforeach
                        <li class="footer"></li>
                    </ul>
                </li>
                @endif
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu mx-2">
                    <a href="#" class="dropdown-toggle text-decoration-none" data-bs-toggle="dropdown">
                        <i class="fa fa-user"></i>
                        <span class="hidden-xs">{{getAuthUser()->username}}</span><i class="caret"></i>
                    </a>

                    <ul class="dropdown-menu">
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="col-xs-6 text-center">
                                <a href="{{ URL::route('profile') }}">
                                    <div><i class="fa fa-briefcase"></i></div>
                                    Profile
                                </a>
                            </div>
                            <div class="col-xs-6 text-center password">
                                <a href="{{ URL::route('change_password') }}">
                                    <div><i class="fa fa-lock"></i></div>
                                Password
                                </a>
                            </div>
                        </li>

                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="col-xs-6 text-center">
                                <a href="{{ URL::route('logout') }}">
                                    <div><i class="fa fa-power-off"></i></div>
                                    Log out
                                </a>
                            </div>
                            <div class="col-xs-6 text-center password">
                                <a href="{{ URL::route('lockscreen') }}">
                                    <div><i class="fa fa-eye-slash"></i></div>
                                    Lock Screen
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears mx-2"></i></a>
                </li>  --}}


                {{--    Upgrade Stype NavItems --}}
                <div class="d-flex align-items-center">

                    <!-- Languages -->
                    <div class="dropdown mx-3 language">

                        <div data-mdb-dropdown-init class="main-language text-reset dropdown-toggle hidden-dropdow-xs"
                            data-bs-toggle="dropdown" href="#" id="navbarDropdownMenuLink" role="button"
                            aria-expanded="false">
                            <a href="javascript:void(0);">
                                <span class="icon-language">
                                    <img src="{{ asset('./images/en.png') }}" alt="English" loading="lazy" />
                                </span>
                                <span class="label-language">
                                    <small class="">EN</small>
                                </span>
                            </a>
                        </div>
                        <ul
                            class="dropdown-menu dropdown-menu-end position-absolute"aria-labelledby="navbarDropdownMenuLink">
                            <li>
                                <a class="dropdown-item">
                                    <img src="{{ asset('./images/en.png') }}" alt="English" loading="lazy" />
                                    English
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item">
                                    <img src="{{ asset('./images/kh.png') }}" alt="English" loading="lazy" />
                                    ភាសាខ្មែរ
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="sepa-menu-header"></div>
                    <!-- Messages -->
                    <div class="dropdown mx-2">
                        <a data-mdb-dropdown-init class="notifi-icon text-reset dropdown-toggle" href="#"
                            id="navbarDropdownMenuLink" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa-regular fa-envelope"><small class="d-none">1</small></i>
                        </a>
                        <ul
                            class="dropdown-menu dropdown-menu-end position-absolute"aria-labelledby="navbarDropdownMenuLink">
                            <li>
                                <a class="dropdown-item" href="#">{{ __('No mail available.') }}</a>
                            </li>

                        </ul>
                    </div>
                    <!-- Notifications -->
                    <div class="dropdown mx-2">
                        <a data-mdb-dropdown-init class="notifi-icon text-reset dropdown-toggle"
                            id="navbarDropdownMenuLink" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa-bell-o fa-regular fa-bell"><small class="notification_badge"></small></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end notifications position-absolute mt-2 p-2 "aria-labelledby="navbarDropdownMenuLink">
                            <li>
                                <a class="dropdown-item notificaton_header" href="#">
                                </a>
                            </li>
                            <li class="dropdown-divider"></li>
                            <li>
                                <div class="dropdown-item">
                                    Top Notifications
                                </div>
                                <ul class="notification_top"></ul>
                            </li>
                            <li class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-primary" href="#">
                                    See All Notifications
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="sepa-menu-header"></div>
                    <!-- Avatar -->
                    <div class="dropdown avatar me-3 ps-4">
                        <a data-mdb-dropdown-init class="dropdown-toggle d-flex align-items-center hidden-dropdow-xs"
                            href="#" id="navbarDropdownMenuAvatar" role="button" aria-expanded="false"
                            data-bs-toggle="dropdown">
                            <img style="border: 1.5px solid #6c6c6c" class="bg-dark bg-opacity-10 object-fit-cover rounded-circle" 
                            width="40px" height="40px" alt="user" loading="lazy"
                            src="{{ $photoPath }}">
                       
                       <span class="hidden-xs mx-3">
                           {{ $authUser->username ?? 'Guest' }}
                           <br>
                           <small>{{ __('Admin') }}</small>
                       </span>
                   
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end position-absolute mt-2 p-2"
                        aria-labelledby="navbarDropdownMenuAvatar">

                            <li>
                                {{-- @role('Teacher') --}}
                                    <a class="dropdown-item" href="#">
                                        <i class="fa fa-solid fa-user"></i>
                                    {{ __('My profile') }}
                                    </a>
                                {{-- @elseif(auth()->user()->hasRole('Student'))
                                    <a class="dropdown-item" href="{{URL::route('student.profile')}}">
                                        <i class="fa fa-solid fa-user"></i>
                                    {{ __('My profile') }}
                                    </a>
                                @else --}}
                                    {{-- <a class="dropdown-item" href="#">
                                        <i class="fa fa-solid fa-user"></i>
                                    {{ __('My profile') }}
                                    </a> --}}
                                {{-- @endrole --}}
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fa fa-solid fa-lock"></i>
                                   {{ __('Password') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fa fa-solid fa-sharp fa-eye-slash"></i>
                                    {{ __('Lock Screen') }}
                                </a>
                            </li>
                            <li class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{route('logout')}}">
                                    <i class="fa fa-solid fa-right-from-bracket"></i>
                                    {{ __('Logout') }}
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>
            </ul>
        </div>
    </nav>
</header>
