{{-- Progress WIS (Admin Only) --}}
@if (auth::user()->id === 226)
    <li>
        <a href="#"
            class="dropdown-toggle {{ isMenuActive(['IndexForm', 'index-audit', 'IT-EMploye_all', 'meeting', 'meeting/audit', 'forfeited/encounter', 'dev/exdo/expired', 'dev/indexProgressLeave', 'dev/histori/leave', 'dev/attendance/reset']) }}"
            data-target="#progress-wis-menu">
            <span><i class="fa fa-wpforms fa-fw"></i> Progress WIS</span>
            <i class="fa fa-chevron-left fa-arrow"></i>
        </a>
        <ul class="nav nav-second-level collapse" id="progress-wis-menu">
            {{-- IT Request Form --}}
            <li>
                <a href="#" class="dropdown-toggle {{ isMenuActive('IndexForm') }}"
                    data-target="#it-request-form-menu">
                    <span><i class="fa fa-wpforms fa-fw"></i> IT Request Form</span>
                    <i class="fa fa-chevron-left fa-arrow"></i>
                </a>
                <ul class="nav nav-second-level collapse" id="it-request-form-menu">
                    <li><a href="{{ route('IndexForm') }}"
                            class="{{ isMenuActive('IndexForm') }}">Requisition</a>
                    </li>
                </ul>
            </li>

            {{-- Create Employee --}}
            <li>
                <a href="#"
                    class="dropdown-toggle {{ isMenuActive(['index-audit', 'IT-EMploye_all']) }}"
                    data-target="#create-employee-menu">
                    <span><i class="glyphicon glyphicon-plus fa-fw"></i> Create Employee</span>
                    <i class="fa fa-chevron-left fa-arrow"></i>
                </a>
                <ul class="nav nav-second-level collapse" id="create-employee-menu">
                    <li><a href="{{ route('index-audit') }}"
                            class="{{ isMenuActive('index-audit') }}"><i
                                class="fa fa-user-md fa-fw"></i> Username & Email</a></li>
                    <li><a href="{{ route('IT-EMploye_all') }}"
                            class="{{ isMenuActive('IT-EMploye_all') }}"><i
                                class="fa fa-users fa-fw"></i> Employes</a></li>
                    <li><a href="{{ route('IT-EMploye_all') }}"
                            class="{{ isMenuActive('IT-EMploye_all') }}"><i
                                class="fa fa-user-times fa-fw"></i> Ex-Employes</a></li>
                </ul>
            </li>

            {{-- Meeting Schedule --}}
            <li>
                <a href="#"
                    class="dropdown-toggle {{ isMenuActive(['meeting', 'meeting/audit']) }}"
                    data-target="#meeting-schedule-menu">
                    <span><i class="fa fa-calendar-times-o fa-fw"></i> Meeting Schedule</span>
                    <i class="fa fa-chevron-left fa-arrow"></i>
                </a>
                <ul class="nav nav-second-level collapse" id="meeting-schedule-menu">
                    <li><a href="{{ route('meeting') }}"
                            class="{{ isMenuActive('meeting') }}">Meeting
                            Room</a></li>
                    <li><a href="{{ route('meeting/audit') }}"
                            class="{{ isMenuActive('meeting/audit') }}">Meeting
                            Auditing</a></li>
                </ul>
            </li>

            {{-- HRD --}}
            <li>
                <a href="#"
                    class="dropdown-toggle {{ isMenuActive(['forfeited/encounter', 'dev/exdo/expired', 'dev/indexProgressLeave', 'dev/histori/leave']) }}"
                    data-target="#hrd-progress-menu">
                    <span><i class="fa fa-calendar-times-o fa-fw"></i> HRD</span>
                    <i class="fa fa-chevron-left fa-arrow"></i>
                </a>
                <ul class="nav nav-second-level collapse" id="hrd-progress-menu">
                    <li><a href="{{ route('forfeited/encounter') }}"
                            class="{{ isMenuActive('forfeited/encounter') }}">Encounter
                            Forfeit</a></li>
                    <li><a href="{{ route('dev/exdo/expired') }}"
                            class="{{ isMenuActive('dev/exdo/expired') }}"
                            title="cutting exdo for exdo expired">Exdo Expired</a></li>
                    <li><a href="{{ route('dev/indexProgressLeave') }}"
                            class="{{ isMenuActive('dev/indexProgressLeave') }}">Leave
                            on Progress</a></li>
                    <li><a href="{{ route('dev/histori/leave') }}"
                            class="{{ isMenuActive('dev/histori/leave') }}">History
                            Leave</a></li>
                </ul>
            </li>

            {{-- Attendance --}}
            <li>
                <a href="#" class="dropdown-toggle {{ isMenuActive('dev/attendance/reset') }}"
                    data-target="#attendance-progress-menu">
                    <span><i class="fa fa-calendar-times-o fa-fw"></i> Attendance</span>
                    <i class="fa fa-chevron-left fa-arrow"></i>
                </a>
                <ul class="nav nav-second-level collapse" id="attendance-progress-menu">
                    <li><a href="{{ route('dev/attendance/reset') }}"
                            class="{{ isMenuActive('dev/attendance/reset') }}">Reset
                            Attendance</a></li>
                </ul>
            </li>
        </ul>
    </li>
@endif