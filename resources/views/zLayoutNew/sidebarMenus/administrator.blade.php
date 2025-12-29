@if (Auth::user()->admin === 1)
    <li>
        <a href="#" class="dropdown-toggle {{ isMenuActive('mgmt-data/*') }}"
            data-target="#admin-management-data-menu">
            <span><i class="fa fa-cogs fa-fw"></i> Management Data<i>(HRD)</i></span>
            <i class="fa fa-chevron-left fa-arrow"></i>
        </a>
        <ul class="nav nav-second-level collapse" id="admin-management-data-menu">
            <li><a href="{{ route('mgmt-data/user') }}" class="{{ isMenuActive('mgmt-data/user') }}">Users</a></li>
            <li><a href="{{ route('mgmt-data/All_User') }}" class="{{ isMenuActive('mgmt-data/All_User') }}">All
                    Users</a>
            </li>
            <li><a href="{{ route('mgmt-data/department') }}"
                    class="{{ isMenuActive('mgmt-data/department') }}">Department</a>
            </li>
            <li><a href="{{ route('mgmt-data/previlege') }}" class="{{ isMenuActive('mgmt-data/previlege') }}">User
                    Previlege</a></li>
        </ul>
    </li>
@endif
