<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="javascript:void(0);" role="button"><i class="fas fa-bars"></i></a></li>
        <!-- li class="nav-item d-none d-sm-inline-block"><a href="index3.html" class="nav-link">Home</a></li>
        <li class="nav-item d-none d-sm-inline-block"><a href="#" class="nav-link">Contact</a></li -->
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <div class="navbar-container container-fluid">
            <div class="dropdown-primary dropdown">
                <div class="dropdown-toggle" data-toggle="dropdown" role="button">
                    <i class="fa fa-user profile mr-2"></i>
                    <!--img src="{{asset('static/glovebox/images/avatar-4.jpg')}}" class="img-radius" alt="User-Profile-Image" -->
                    <span>{{displayString($currentUser->username, 'UCF')}}</span>
                    <i class="feather icon-chevron-down"></i>
                </div>
                <ul class="show-notification profile-notification dropdown-menu" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                    <!-- li><a href="javascript:void(0);"><i class="fas fa-solid fa-cogs fa-fw"></i> Settings</a></li -->
                    <li><a href="{{route('admin.profile')}}"><i class="fas fa-solid  fa-user fa-fw"></i> Profile</a></li>
                    <li><a href="{{route('users.edit.password')}}"><i class="fa fa-solid fa-key"></i> Change Password</a></li>

                    <li><a href="{{route('admin.logout')}}"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>

    </ul>
</nav>