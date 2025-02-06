<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar shadow">
    <section class="sidebar">
        <!-- sidebar menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li>
                <a href="{{ URL::route('dashboard.index') }}" class="text-decoration-none">
                    <i class="fa fa-solid fa-chart-line"></i> <span> {{ __('Dashboard') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ URL::route('department.index') }}" class="text-decoration-none">
                    <i class="fa fa-landmark-flag"></i> <span>{{ __('Departments') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ URL::route('ticket.index') }}" class="text-decoration-none">
                    <i class="fa fa-regular fa-address-card"></i> <span>{{ __('Tickets') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ URL::route('status.index') }}" class="text-decoration-none">
                    <i class="fa fa-sliders"></i> <span>{{ __('Statuses') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ URL::route('priority.index') }}" class="text-decoration-none">
                    <i class="fa fa-font-awesome"></i> <span>{{ __('Priorities') }}</span>
                </a>
            </li>
            <li>
                <a href="#" class="text-decoration-none">
                    <i class="fa fa-users"></i> <span>{{ __('Users') }}</span>
                </a>
            </li>
            <li>
                <a href="#" class="text-decoration-none">
                    <i class="fa fa-users"></i> <span>{{ __('User roles') }}</span>
                </a>
            </li>
            <li class="treeview">
                <a href="#" class="text-decoration-none">
                    <i class="fa fa-cogs"></i> <span>{{ __('Settings') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="#" class="text-decoration-none">
                            <i class="fa fa-solid fa-person-dots-from-line"></i><span>{{ __('Translations') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
