<nav class="navbar navbar-top">
    {{-- <div class="navbar-toggle-area" id="navbarToggleArea"></div> --}}
    <div class="branding-navbar-top">
        <a href="{{ route('index') }}">
            Wide Information System
        </a>
    </div>


    <button class="sidebar-toggle" id="sidebarToggle">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>

    <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                aria-expanded="false">
                <i class="fa fa-cog fa-fw"></i> Setting
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="{{ route('employes/profile/index') }}"><i class="fa fa-user fa-fw"></i> Profile</a></li>
                <li><a href="{{ route('get_change-password') }}"><i class="fa fa-user fa-fw"></i> Change Password</a>
                </li>
                <li role="separator" class="divider"></li>
                <li>
                    <a href="{{ route('logout') }}"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
