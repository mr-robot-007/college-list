<aside class="main-sidebar sidebar-dark-primary elevation-4" style="">
    <a href="{{route('admin.dashboard')}}" class="brand-link">
        <img src="/static/logo.jpeg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{env('APP_NAME')}}</span>
    </a>

    <div class="sidebar">

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item {{((@$parent=='dashboard') ? 'menu-open' : '')}}">
                    <a href="{{route('admin.dashboard')}}" class="nav-link {{((@$parent=='dashboard' && @$child=='dashboard') ? 'active' : '')}}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard </p>
                    </a>
                    <a href="{{route('admin.admissions')}}" class="nav-link {{((@$parent=='admissions' && @$child=='admissions') ? 'active' : '')}}">
                        <i class="nav-icon ion ion-stats-bars"></i>
                        <p>Admissions </p>
                    </a>
                </li>


                @if(is_admin())
                <li class="nav-item {{((@$parent=='administration') ? 'menu-open' : '')}}">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Administration <i class="fas fa-angle-right right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('admin.users')}}" class="nav-link ml-3 {{((@$parent=='administration' && @$child=='userlist') ? 'active' : '')}}">
                                <i class="far fa-circle {{((@$parent=='administration' && @$child=='userlist') ? 'text-danger' : 'text-info')}}"></i>
                                <p class="ml-2">Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('admin.institutes')}}" class="nav-link ml-3 {{((@$parent=='administration' && @$child=='institutelist') ? 'active' : '')}}">
                                <i class="far fa-circle {{((@$parent=='administration' && @$child=='institutelist') ? 'text-danger' : 'text-info')}}"></i>
                                <p class="ml-2">Universities</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('admin.courses')}}" class="nav-link ml-3 {{((@$parent=='administration' && @$child=='courselist') ? 'active' : '')}}">
                                <i class="far fa-circle {{((@$parent=='administration' && @$child=='courselist') ? 'text-danger' : 'text-info')}}"></i>
                                <p class="ml-2">Courses</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('admin.visits')}}" class="nav-link ml-3 {{((@$parent=='administration' && @$child=='visitlist') ? 'active' : '')}}">
                                <i class="far fa-circle {{((@$parent=='administration' && @$child=='visitlist') ? 'text-danger' : 'text-info')}}"></i>
                                <p class="ml-2">Course Visits</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif()

               

                <li class="nav-item">
                    <a href="{{route('admin.logout')}}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt text-info"></i>
                        <p class="text">Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<div class="content-wrapper">