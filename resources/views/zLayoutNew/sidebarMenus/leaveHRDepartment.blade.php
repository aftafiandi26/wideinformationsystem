@if (auth()->user()->dept_category_id === 3 && auth()->user()->hr == false)
    <li>
        <a href="#"
            class="dropdown-toggle {{ isMenuActive(['employee', 'hr/ex-employes/index', 'contract-employee', 'indexEndEmployee', 'projectHRD']) }}"
            data-target="#hr-deparmtent-management-data-menu">
            <span><i class="fa fa-user fa-fw"></i> Management Employee</span>
            <i class="fa fa-chevron-left fa-arrow"></i>
        </a>
        <ul class="nav nav-second-level collapse" id="hr-deparmtent-management-data-menu">
            <li>
                <a href="{{ route('employee') }}" class="{{ isMenuActive('employee') }}">Employee</a>
            </li>
            <li>
                <a href="{{ route('hr/ex-employes/index') }}"
                    class="{{ isMenuActive('hr/ex-employes/index') }}">Ex-Employee</a>
            </li>
            <li>
                <a href="#" class="dropdown-toggle {{ isMenuActive(['contract-employee', 'indexEndEmployee']) }}"
                    data-target="#hr-deparmtent-management-data-menu-employee-contract">Employee Contract</span>
                    <i class="fa fa-chevron-left fa-arrow"></i>
                </a>
                <ul class="nav nav-second-level collapse" id="hr-deparmtent-management-data-menu-employee-contract">
                    <li>
                        <a href="{{ route('contract-employee') }}" class="{{ isMenuActive('contract-employee') }}">End
                            Of Contract</a>
                    </li>
                    <li>
                        <a href="{{ route('indexEndEmployee') }}"
                            class="{{ isMenuActive('indexEndEmployee') }}">Contract Expired Soon</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('projectHRD') }}" class="{{ isMenuActive('projectHRD') }}">Projects</a>
            </li>
        </ul>
    </li>
    <li>
        <a href="#"
            class="dropdown-toggle {{ isMenuActive(['gm/summary/attendance/index', 'gm/employee-time-sheet/index', 'hr/summary/attendance/index']) }}"
            data-target="#hr-deparmtent-management-att-menu">
            <span><i class="fa fa-calendar fa-fw"></i> Management Attendance</span>
            <i class="fa fa-chevron-left fa-arrow"></i>
            <ul class="nav nav-second-level collapse" id="hr-deparmtent-management-att-menu">
                <li><a href="{{ route('hr/summary/attendance/index') }}"
                        class="{{ isMenuActive('hr/summary/attendance/index') }}">Attendance Employee</a></li>
                <li>
                    <a href="{{ route('gm/summary/attendance/index') }}"
                        class="{{ isMenuActive('gm/summary/attendance/index') }}">Attendance Projects</a>
                </li>
                <li>
                    <a href="{{ route('gm/employee-time-sheet/index') }}"
                        class="{{ isMenuActive('gm/employee-time-sheet/index') }}">Attendance Projects Time Sheet</a>
                </li>
            </ul>
        </a>
    </li>
@endif
