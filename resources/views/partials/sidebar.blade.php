<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
        <img src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">پنل مدیریت</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <div>
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="info">
                    <a href="#" class="d-block">هلدینگ برادران مشرفی</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    {{--                    <li class="nav-item has-treeview menu-open">--}}
                    {{--                        <a href="#" class="nav-link active">--}}
                    {{--                            <i class="nav-icon fa fa-dashboard"></i>--}}
                    {{--                            <p>--}}
                    {{--                                داشبورد--}}
                    {{--                                <i class="right fa fa-angle-left"></i>--}}
                    {{--                            </p>--}}
                    {{--                        </a>--}}
                    {{--                        <ul class="nav nav-treeview">--}}
                    {{--                            <li class="nav-item">--}}
                    {{--                                <a href="/" class="nav-link active">--}}
                    {{--                                    <i class="fa fa-circle-o nav-icon"></i>--}}
                    {{--                                    <p>پنل</p>--}}
                    {{--                                </a>--}}
                    {{--                            </li>--}}
                    {{--                        </ul>--}}
                    {{--                    </li>--}}
                    <li class="nav-item">
                        <a href="/" class="nav-link {{active_sidebar(['/'])? 'active' : ''}}">
                            <i class="nav-icon fa fa-th"></i>
                            <p>
                                داشبورد
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/users" class="nav-link {{active_sidebar(['users'])? 'active' :''}}">
                            <i class="nav-icon fa fa-users"></i>
                            <p>کارمندان
                                <span class="right badge badge-light">{{convert_number_to_persian(\App\Models\User::count())}}</span>
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/tasks" class="nav-link {{active_sidebar(['tasks','tasks/*'])? 'active' :''}}">
                            <i class="nav-icon fa fa-tasks"></i>
                            <p>وظایف</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/reports" class="nav-link {{active_sidebar(['reports','reports/*'])? 'active' :''}}">
                            <i class="nav-icon fa fa-file-text"></i>
                            <p>گزارشات</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/notes" class="nav-link {{active_sidebar(['notes','notes/*'])? 'active' :''}}">
                            <i class="nav-icon fa fa-sticky-note"></i>
                            <p>یادداشت ها</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</aside>
