<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar shadow">
    <section class="sidebar">
        <!-- sidebar menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li>
                <a href="{{ URL::route('dashboard') }}" class="text-decoration-none">
                <i class="fa fa-solid fa-chart-line"></i> <span> {{ __('Dashboard') }}</span>
                </a>
            </li>
            <li>
                <a href="#" class="text-decoration-none">
                <i class="fa fa-regular fa-address-card"></i> <span>{{ __('Profile') }}</span>
                </a>
            </li>
                    <li class="treeview">
                        <a href="#" class="text-decoration-none">
                            <i class="fa fa-solid fa-chalkboard-user"></i> <span>{{ __('Teacher Management') }}</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <a href="#" class="text-decoration-none">
                                <i class="fa fa-solid fa-person-dots-from-line"></i><span>{{ __('Teachers') }}</span>
                                </a>
                            </li>

                            <li>
                                <a href="#" class="text-decoration-none">

                                    <i class="fa-solid fa-list-check"></i><span>{{ __('Teacher Statistics') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
