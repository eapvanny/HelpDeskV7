@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\Auth;
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
        <button id="contact-user" class="btn btn-contact" data-url="{{ route('get.support') }}" data-bs-toggle="modal"
            data-bs-target="#showContact">
            {{ __('Contact Us') }}
        </button>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                {{--    Upgrade Stype NavItems --}}
                <div class="d-flex align-items-center">

                    <!-- Languages -->
                    <div class="dropdown mx-3 language">
                        <div data-mdb-dropdown-init class="main-language text-reset dropdown-toggle hidden-dropdow-xs"
                            data-bs-toggle="dropdown" href="#" id="navbarDropdownMenuLink" role="button"
                            aria-expanded="false">
                            <a href="javascript:void(0);">
                                <span class="icon-language">
                                    <img src="{{ asset('./images/' . session('user_lang', 'kh') . '.png') }}"
                                        alt="{{ session('user_lang', 'kh') == 'kh' ? 'Khmer' : 'English' }}"
                                        loading="lazy" />
                                </span>
                                <span class="label-language">
                                    <small>{{ session('user_lang', 'kh') == 'kh' ? 'KH' : 'EN' }}</small>
                                </span>
                            </a>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end position-absolute"
                            aria-labelledby="navbarDropdownMenuLink">
                            <li>
                                <a class="dropdown-item" href="{{ route('user.set_lang', 'en') }}">
                                    <img src="{{ asset('./images/en.png') }}" alt="English" loading="lazy" />
                                    English
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('user.set_lang', 'kh') }}">
                                    <img src="{{ asset('./images/kh.png') }}" alt="Khmer" loading="lazy" />
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
                            <i class="fa-bell-o fa-regular fa-bell">
                                <small class="notification_badge">
                                    <?php
                                    $tickets = App\Models\Ticket::whereNull('request_status')->orderBy('created_at', 'desc')->limit(5)->get();
                                    $ticketCount = $tickets->count();
                                    echo $ticketCount > 0 ? $ticketCount : '';
                                    ?>
                                </small>
                            </i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end notifications position-absolute mt-2 p-2"
                            aria-labelledby="navbarDropdownMenuLink">
                            <li>
                                <a class="dropdown-item notificaton_header" href="#">
                                    Notifications
                                </a>
                            </li>
                            <li class="dropdown-divider"></li>
                            <li>
                                <ul class="notification_top">
                                    <?php
                                    foreach ($tickets as $ticket) {
                                        echo '<li class="notification-item" style="color: #777">';
                                        echo '<div class="notification-subject"><strong>' . htmlspecialchars($ticket->subject) . '</strong> (' . htmlspecialchars(substr($ticket->description, 0, 50)) . ')</div>';
                                        echo '</li>';
                                    }
                                    ?>
                                </ul>
                            </li>
                            @if ($ticketCount != 0)
                                <li class="dropdown-divider"></li>
                            @endif
                            <li>
                                <a class="dropdown-item text-primary" href="{{ route('ticket.index') }}">
                                    See All Tickets
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
                            <img style="border: 1.5px solid #6c6c6c"
                                class="bg-dark bg-opacity-10 object-fit-cover rounded-circle" width="40px"
                                height="40px" alt="user" loading="lazy" src="{{ $photoPath }}">

                            <span class="hidden-xs mx-3">
                                {{ $authUser->username ?? 'Guest' }}
                                <br>
                                <small>{{ $authUser->role->name ?? 'Guest' }}</small>
                            </span>

                        </a>
                        <ul class="dropdown-menu dropdown-menu-end position-absolute mt-2 p-2"
                            aria-labelledby="navbarDropdownMenuAvatar">

                            <li>
                                <a class="dropdown-item" href="{{ URL::route('profile') }}">
                                    <i class="fa fa-solid fa-user"></i>
                                    {{ __('My Profile') }}
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ URL::route('change_password') }}">
                                    <i class="fa fa-solid fa-lock"></i>
                                    {{ __('Password') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ URL::route('lockscreen') }}">
                                    <i class="fa fa-solid fa-sharp fa-eye-slash"></i>
                                    {{ __('Lock Screen') }}
                                </a>
                            </li>
                            <li class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}">
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
<div class="modal modal-lg fade" id="showContact" tabindex="-1" role="dialog" aria-labelledby="showContactLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h5 class="modal-title" id="showContactLabel">{{ __('Supporter') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" id="btnClose"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body d-flex justify-content-center gap-3 flex-wrap" id="modal-body-content">
                <!-- Contacts will be loaded dynamically -->
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('Close') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#contact-user').on('click', function() {
                let url = $(this).data('url');

                $.ajax({
                    url: url,
                    type: 'GET',
                    beforeSend: function() {
                        $('#modal-body-content').html(
                            '<p>Loading contacts...</p>');
                    },
                    success: function(response) {
                        $('#modal-body-content').html(response);
                    },
                    error: function() {
                        $('#modal-body-content').html(
                            '<p class="text-danger">Failed to load contacts.</p>');
                    }
                });
            });
        });
    </script>
@endpush
