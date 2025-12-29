@if (Auth::user()->hr === 1)
    <li>
        <a href="#" class="dropdown-toggle {{ isMenuActive(['stationery/*', 'indexKategoryStationary']) }}"
            data-target="#stationery-menu">
            <span><i class="fa fa-pencil fa-fw"></i>Stationery</span>
            <i class="fa fa-chevron-left fa-arrow"></i>
        </a>
        <ul class="nav nav-second-level collapse" id="stationery-menu">
            <li><a href="{{ route('stationery/atk/index') }}"
                    class="{{ isMenuActive('stationery/atk/index') }}">Stationery
                    Stock</a></li>
            <li><a href="{{ route('stationery/mineral/index') }}"
                    class="{{ isMenuActive('stationery/mineral/index') }}">Mineral
                    Stock</a></li>
            <li><a href="{{ route('indexKategoryStationary') }}"
                    class="{{ isMenuActive('indexKategoryStationary') }}">Category
                    Stationery</a></li>
            <li><a href="{{ route('stationery/summary/stock/index') }}"
                    class="{{ isMenuActive('stationery/summary/stock/index') }}">Summary
                    Stationery</a></li>
        </ul>
    </li>
    <li>
        <a href="#"
            class="dropdown-toggle {{ isMenuActive(['employee', 'End-Contract-Staff', 'hr_mgmt-data/department', 'hr_mgmt-data/project', 'hr_mgmt-data/previlege', 'hr/summary/attendance/index', 'hr/ex-employes/index', 'contract-employee', 'indexEndEmployee']) }}"
            data-target="#hr-management-data-menu">
            <span><i class="fa fa-user fa-fw"></i> Management Employes</span>
            <i class="fa fa-chevron-left fa-arrow"></i>
        </a>
        <ul class="nav nav-second-level collapse" id="hr-management-data-menu">
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
                    <li><a href="{{ route('End-Contract-Staff') }}"
                            class="{{ isMenuActive('End-Contract-Staff') }}">Staff End
                            Contract</a></li>
                </ul>
            </li>
            <li><a href="{{ route('hr_mgmt-data/department') }}"
                    class="{{ isMenuActive('hr_mgmt-data/department') }}">Department</a>
            </li>
            <li><a href="{{ route('hr_mgmt-data/project') }}"
                    class="{{ isMenuActive('hr_mgmt-data/project') }}">Project</a>
            </li>
            <li><a href="{{ route('hr_mgmt-data/previlege') }}"
                    class="{{ isMenuActive('hr_mgmt-data/previlege') }}">Employee
                    Previlege</a></li>
        </ul>
    </li>
    <li>
        <a href="#"
            class="dropdown-toggle {{ isMenuActive(['hr/entitled/index', 'hr_mgmt-data/leaveTransactionReport', 'coordinator/leave-balance/index', 'hrd/exdo-leave/index', 'hr_mgmt-data/leave/tempInitialLeave', 'forfeited/index', 'leave/reschedule/index', 'hrd/summary/leave/index', 'index/sicked', 'sicked/summary', 'hr_mgmt-data/initial']) }}"
            data-target="#hr-management-leave-menu">
            <span><i class="fa fa-calendar fa-fw"></i> Management Leave</span>
            <i class="fa fa-chevron-left fa-arrow"></i>
        </a>
        <ul class="nav nav-second-level collapse" id="hr-management-leave-menu">
            <li>
                <a href="#"
                    class="dropdown-toggle {{ isMenuActive(['hr/entitled/index', 'hr_mgmt-data/leaveTransactionReport', 'hr_mgmt-data/initial']) }}"
                    data-target="#hr-management-leave-menu-leave-report">
                    <span>Leave Report</span>
                    <i class="fa fa-chevron-left fa-arrow"></i>
                </a>
                <ul class="nav nav-second-level collapse" id="hr-management-leave-menu-leave-report">
                    <li><a href="{{ route('hr_mgmt-data/initial') }}"
                            class="{{ isMenuActive('hr_mgmt-data/initial') }}">Entitlement
                            Exdo Leave</a></li>
                    <li>
                        <a href="{{ route('hr/entitled/index') }}"
                            class="{{ isMenuActive('hr/entitled/index') }}">Leave Entitled Report</a>
                    </li>
                    <li>
                        <a href="{{ route('hr_mgmt-data/leaveTransactionReport') }}"
                            class="{{ isMenuActive('hr_mgmt-data/leaveTransactionReport') }}">Leave Transaction
                            Report</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('coordinator/leave-balance/index') }}"
                    class="{{ isMenuActive('coordinator/leave-balance/index') }}">Annual Leave</a>
            </li>
            <li>
                <a href="{{ route('hrd/exdo-leave/index') }}" class="{{ isMenuActive('hrd/exdo-leave/index') }}">Exdo
                    Leave</a>
            </li>
            <li>
                <a href="{{ route('hr_mgmt-data/leave/tempInitialLeave') }}"
                    class="{{ isMenuActive('hr_mgmt-data/leave/tempInitialLeave') }}">Initial Leave Transaction</a>
            </li>
            <li>
                <a href="{{ route('forfeited/index') }}" class="{{ isMenuActive('forfeited/index') }}">Forfeited
                    Leave</a>
            </li>
            <li>
                <a href="{{ route('leave/reschedule/index') }}"
                    class="{{ isMenuActive('leave/reschedule/index') }}">Reschedule Leave</a>
            </li>
            <li>
                <a href="{{ route('hrd/summary/leave/index') }}"
                    class="{{ isMenuActive('hrd/summary/leave/index') }}">Summary Leave</a>
            </li>

            <li>
                <a href="#" class="dropdown-toggle {{ isMenuActive(['index/sicked', 'sicked/summary']) }}"
                    data-target="#hr-management-leave-menu-medical-record">
                    <span>Medical Record</span>
                    <i class="fa fa-chevron-left fa-arrow"></i>
                </a>
                <ul class="nav nav-second-level collapse" id="hr-management-leave-menu-medical-record">
                    <li>
                        <a href="{{ route('index/sicked') }}" class="{{ isMenuActive('index/sicked') }}">Employee
                            Wellness</a>
                    </li>
                    <li>
                        <a href="{{ route('sicked/summary') }}" class="{{ isMenuActive('sicked/summary') }}">Summary
                            Employee Wellness</a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li>
        <a href="#"
            class="dropdown-toggle {{ isMenuActive(['hr/entitled/index', 'hr_mgmt-data/leaveTransactionReport', 'coordinator/leave-balance/index', 'hrd/exdo-leave/index', 'hr_mgmt-data/leave/tempInitialLeave', 'forfeited/index', 'leave/reschedule/index', 'hrd/summary/leave/index', 'index/sicked', 'sicked/summary']) }}"
            data-target="#hr-management-attendance-menu">
            <span><i class="fa fa-calendar fa-fw"></i> Management Attendance</span>
            <i class="fa fa-chevron-left fa-arrow"></i>
            <ul class="nav nav-second-level collapse" id="hr-management-attendance-menu">
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
