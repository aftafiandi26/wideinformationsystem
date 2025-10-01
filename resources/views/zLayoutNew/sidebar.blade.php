@php
    // Helper function untuk active class
    function isMenuActive($routes)
    {
        if (is_string($routes)) {
            return request()->routeIs($routes);
        }
        foreach ($routes as $route) {
            if (request()->routeIs($route)) {
                return 'active';
            }
        }
        return '';
    }
@endphp

<nav id="sidebar" class="sidebar">
    <div class="logo-container">
        <a href="{{ route('index') }}">
            <img src="{{ asset('assets/WIS_LOGO_2025_V2.png') }}" alt="Logo" class="img-responsive">
        </a>
    </div>

    <div class="user-profile">
        @if (Auth::user()->prof_pict === null)
            <div class="user-name">
                <i class="fa fa-user-circle fa-3x" style="color: #ecf0f1;"></i><br>
                <small>Hi, <b>{{ Auth::user()->first_name }}</b></small>
            </div>
        @else
            <img src="https://picsum.photos/seed/picsum/200/300" class="img-circle" alt="Profile Picture">
            <div class="user-name">
                <small>Hi, <b>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</b></small>
            </div>
        @endif
    </div>
    {{-- Simple Search - Desktop Only --}}
    <div class="sidebar-search">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search menu..." id="sidebarSearch">
        </div>
    </div>
    {{-- Sidebar Menu Container --}}
    <div class="sidebar-menu-container">
        <ul class="nav" id="side-menu">

            {{-- Dashboard --}}
            <li>
                <a href="{{ route('index') }}" class="{{ isMenuActive('index') }}">
                    <i class="fa fa-dashboard fa-fw"></i> Dashboard
                </a>
            </li>

            {{-- Leave Management Menu --}}
            @if (Auth::user()->user === 1 ||
                    Auth::user()->hr === 1 ||
                    Auth::user()->hd === 1 ||
                    Auth::user()->hrd === 1 ||
                    Auth::user()->koor === 1 ||
                    Auth::user()->gm === 1 ||
                    Auth::user()->spv === 1 ||
                    Auth::user()->pm === 1 ||
                    Auth::user()->producer === 1 ||
                    Auth::user()->infiniteApprove === 1)
                <li>
                    <a href="#"
                        class="dropdown-toggle {{ isMenuActive(['leave/*', 'all_employes/leave/*', 'employee/forfeited/*', 'Koordinator/*', 'Supervisor/*', 'ProjectManager/*', 'leave/HD_approval', 'head-of-approval/*', 'index-Grafik', 'index-History', 'leave/GM_approval', 'leave/alltransaction', 'leave/calender/*', 'leave/HRD_approval', 'indexAllEmployee', 'indexApprovalInfinite', 'leave/HR_ver', 'indexSummaryVerified', 'PipeLineTechnicalIndexApproval', 'facilities/admin/verify']) }}"
                        data-target="#leave-menu">
                        <span><i class="fa fa-file-text-o fa-fw"></i> Leave</span>
                        <i class="fa fa-chevron-left fa-arrow"></i>
                    </a>
                    <ul class="nav nav-second-level collapse" id="leave-menu">
                        {{-- Basic Leave Functions --}}
                        <li><a href="{{ route('leave/apply') }}" class="{{ isMenuActive('leave/apply') }}">Applying
                                Leave</a></li>
                        <li><a href="{{ route('all_employes/leave/transaction/migrate') }}"
                                class="{{ isMenuActive('all_employes/leave/transaction/migrate') }}">Leave
                                Transaction</a></li>
                        <li><a href="{{ route('employee/forfeited/index') }}"
                                class="{{ isMenuActive('employee/forfeited/index') }}">Forfeited</a>
                        </li>
                        <li><a href="{{ route('leave/calender/index') }}"
                                class="{{ isMenuActive('leave/calender/index') }}">Calendar of
                                Leave</a></li>

                        {{-- Coordinator Leave Approval --}}
                        @if (Auth::user()->koor === 1)
                            <li><a href="{{ route('Koordinator/indexApproval') }}"
                                    class="{{ isMenuActive('Koordinator/indexApproval') }}">Leave
                                    Approval<br><i>(Coordinator)</i></a></li>
                            <li><a href="{{ route('indexSummaryApprovedCoordinator') }}"
                                    class="{{ isMenuActive('indexSummaryApprovedCoordinator') }}">Summary
                                    Approved</a></li>
                        @endif

                        {{-- Supervisor Leave Approval --}}
                        @if (Auth::user()->spv === 1)
                            <li><a href="{{ route('Supervisor/indexApproval') }}"
                                    class="{{ isMenuActive('Supervisor/indexApproval') }}">Leave
                                    Approval<br><i>(SPV)</i></a></li>
                            <li><a href="{{ route('indexSummaryApprovedSPV') }}"
                                    class="{{ isMenuActive('indexSummaryApprovedSPV') }}">Summary
                                    Approved</a></li>
                        @endif

                        {{-- Project Manager Leave Approval --}}
                        @if (Auth::user()->pm === 1)
                            <li><a href="{{ route('ProjectManager/indexApproval') }}"
                                    class="{{ isMenuActive('ProjectManager/indexApproval') }}">Leave
                                    Approval<i>(pm)</i></a></li>
                            <li><a href="{{ route('indexSummaryApprovedPM') }}"
                                    class="{{ isMenuActive('indexSummaryApprovedPM') }}">Summary
                                    Approved</a></li>
                        @endif

                        {{-- Head of Department Leave Approval --}}
                        @if (Auth::user()->hd === 1)
                            <li>
                                <a href="{{ route('leave/HD_approval') }}"
                                    class="{{ isMenuActive('leave/HD_approval') }}">
                                    Leave Approval<br><i>(<?php $dept = DB::table('dept_category')
                                        ->where('dept_category.id', '=', Auth::user()->dept_category_id)
                                        ->value('dept_category_name');
                                    echo $dept; ?> Department)</i>
                                </a>
                            </li>
                            @if (auth::user()->dept_ApprovedHOD === 1)
                                <li><a href="{{ route('head-of-approval/index') }}"
                                        class="{{ isMenuActive('head-of-approval/index') }}">Leave
                                        Approval<i>(Head of Department)</i></a></li>
                            @endif
                            @if (Auth::user()->dept_category_id === 6)
                                <li><a href="{{ route('index-Grafik') }}"
                                        class="{{ isMenuActive('index-Grafik') }}">Summary of
                                        Leave</a></li>
                            @endif
                            <li><a href="{{ route('index-History') }}"
                                    class="{{ isMenuActive('index-History') }}">History of
                                    Leave</a></li>
                        @endif

                        {{-- General Manager Leave Approval --}}
                        @if (Auth::user()->gm === 1)
                            <li><a href="{{ route('leave/GM_approval') }}"
                                    class="{{ isMenuActive('leave/GM_approval') }}">Leave
                                    Approval<i>(gm)</i></a></li>
                            <li><a href="{{ route('leave/alltransaction') }}"
                                    class="{{ isMenuActive('leave/alltransaction') }}">All Leave
                                    Transaction<i>(gm)</i></a></li>
                        @endif

                        {{-- HRD Leave Approval --}}
                        @if (Auth::user()->hrd === 1)
                            <li><a href="{{ route('leave/HRD_approval') }}"
                                    class="{{ isMenuActive('leave/HRD_approval') }}">Leave
                                    Approval<br><i>(Approval For Head of Department)</i></a></li>
                            <li><a href="{{ route('indexAllEmployee') }}"
                                    class="{{ isMenuActive('indexAllEmployee') }}">Leave
                                    Approval<br><i>(Employee Verification)</i></a></li>
                        @endif

                        {{-- Infinite Leave Approval --}}
                        @if (Auth::user()->infiniteApprove === 1)
                            <li><a href="{{ route('indexApprovalInfinite') }}"
                                    class="{{ isMenuActive('indexApprovalInfinite') }}">Kinema
                                    Leave Approval</a></li>
                        @endif

                        {{-- HR Leave Verification --}}
                        @if (Auth::user()->hr === 1)
                            <li><a href="{{ route('leave/HR_ver') }}" class="{{ isMenuActive('leave/HR_ver') }}">Leave
                                    Verification</a></li>
                            <li><a href="{{ route('indexSummaryVerified') }}"
                                    class="{{ isMenuActive('indexSummaryVerified') }}">Summary
                                    Verified</a></li>
                        @endif

                        {{-- Technical Pipeline Leave Approval --}}
                        @if (Auth::user()->level_hrd === 'Senior Technical')
                            <li><a href="{{ route('PipeLineTechnicalIndexApproval') }}"
                                    class="{{ isMenuActive('PipeLineTechnicalIndexApproval') }}">Leave
                                    Approval<i>(beta)</i></a></li>
                        @endif

                        {{-- Facility Admin Leave Summary --}}
                        @if (Auth::user()->dept_category_id == 5 and Auth::user()->position == 'Admin Facility')
                            <li><a href="{{ route('facilities/admin/verify') }}"
                                    class="{{ isMenuActive('facilities/admin/verify') }}">Summary
                                    Leave<small>(Facility)</small></a></li>
                        @endif
                    </ul>
                </li>
            @endif

            {{-- Leave Reports (HRD) --}}
            @if (Auth::user()->hrd === 1)
                <li>
                    <a href="#"
                        class="dropdown-toggle {{ isMenuActive(['hr/entitled/*', 'management-data/historical']) }}"
                        data-target="#leave-report-menu">
                        <span><i class="fa fa-bars fa-fw"></i> Leave Report</span>
                        <i class="fa fa-chevron-left fa-arrow"></i>
                    </a>
                    <ul class="nav nav-second-level collapse" id="leave-report-menu">
                        <li><a href="{{ route('hr/entitled/index') }}"
                                class="{{ isMenuActive('hr/entitled/index') }}">Leave Entitled
                                Report</a></li>
                        <li><a href="{{ route('management-data/historical') }}"
                                class="{{ isMenuActive('management-data/historical') }}">Histori
                                Leave Transaction</a></li>
                    </ul>
                </li>

                {{-- HRD Management --}}
                <li>
                    <a href="#" class="dropdown-toggle {{ isMenuActive(['Employee-HRD', 'rusun-HRD']) }}"
                        data-target="#hrd-management-menu">
                        <span><i class="fa fa-cloud fa-fw"></i> Management HRD</span>
                        <i class="fa fa-chevron-left fa-arrow"></i>
                    </a>
                    <ul class="nav nav-second-level collapse" id="hrd-management-menu">
                        <li><a href="{{ route('Employee-HRD') }}"
                                class="{{ isMenuActive('Employee-HRD') }}">Employee</a></li>
                        <li><a href="{{ route('rusun-HRD') }}" class="{{ isMenuActive('rusun-HRD') }}">Rusun</a>
                        </li>
                    </ul>
                </li>
            @endif

            {{-- Attendance Menu --}}
            @if (auth()->user()->hd == false && auth()->user()->gm == false)
                <li>
                    <a href="{{ route('attendance/index') }}" class="{{ isMenuActive('attendance/index') }}">
                        <i class="fa fa-bar-chart fa-fw"></i> Attendance
                    </a>
                </li>
            @endif

            @if (auth()->user()->hd == true || auth()->user()->gm == true)
                <li>
                    <a href="#"
                        class="dropdown-toggle {{ isMenuActive(['attendance/index', 'hod/attendance/summary']) }}"
                        data-target="#attendance-menu">
                        <span><i class="fa fa-soundcloud fa-fw"></i> Attendance</span>
                        <i class="fa fa-chevron-left fa-arrow"></i>
                    </a>
                    <ul class="nav nav-second-level collapse" id="attendance-menu">
                        <li><a href="{{ route('attendance/index') }}"
                                class="{{ isMenuActive('attendance/index') }}">Attendance</a>
                        </li>
                        <li><a href="{{ route('hod/attendance/summary') }}"
                                class="{{ isMenuActive('hod/attendance/summary') }}">Summary</a>
                        </li>
                    </ul>
                </li>
            @endif

            {{-- Public Holiday Menu (HR Department) --}}
            @if (auth::user()->dept_category_id === 3)
                <li>
                    <a href="#"
                        class="dropdown-toggle {{ isMenuActive(['indexAllSummary', 'indexUnpaidLeave', 'indexViewOffYears']) }}"
                        data-target="#public-holiday-menu">
                        <span><i class="fa fa-soundcloud fa-fw"></i> Public Holiday</span>
                        <i class="fa fa-chevron-left fa-arrow"></i>
                    </a>
                    <ul class="nav nav-second-level collapse" id="public-holiday-menu">
                        <li><a href="{{ route('indexAllSummary') }}"
                                class="{{ isMenuActive('indexAllSummary') }}">All of Summary</a>
                        </li>
                        @if (auth::user()->level_hrd === 'Payroll')
                            <li><a href="{{ route('indexUnpaidLeave') }}"
                                    class="{{ isMenuActive('indexUnpaidLeave') }}">Unpaid
                                    Employee</a></li>
                        @endif
                        @if (auth::user()->hr === 1)
                            <li><a href="{{ route('indexViewOffYears') }}"
                                    class="{{ isMenuActive('indexViewOffYears') }}">Public
                                    Holiday</a></li>
                        @endif
                    </ul>
                </li>
            @endif

            {{-- IT Department Menu --}}
            @if (auth::user()->dept_category_id === 1)
                {{-- WS Availability --}}
                <li>
                    <a href="#"
                        class="dropdown-toggle {{ isMenuActive(['workstations/availability/*', 'History/Availability', 'legend']) }}"
                        data-target="#ws-availability-menu">
                        <span><i class="fa fa-home fa-fw"></i> WS Availability</span>
                        <i class="fa fa-chevron-left fa-arrow"></i>
                    </a>
                    <ul class="nav nav-second-level collapse" id="ws-availability-menu">
                        <li><a href="{{ route('workstations/availability/add') }}"
                                class="{{ isMenuActive('workstations/availability/add') }}">Add
                                Workstation</a></li>
                        <li><a href="{{ route('workstations/availability/index') }}"
                                class="{{ isMenuActive('workstations/availability/index') }}">List
                                Workstation</a></li>
                        <li><a href="{{ route('workstations/availability/idle/index') }}"
                                class="{{ isMenuActive('workstations/availability/idle/index') }}">Workstations
                                Idle</a></li>
                        <li><a href="{{ route('workstations/availability/fails/index') }}"
                                class="{{ isMenuActive('workstations/availability/fails/index') }}">Workstations
                                Fail</a></li>
                        <li><a href="{{ route('workstations/availability/scrapped/index') }}"
                                class="{{ isMenuActive('workstations/availability/scrapped/index') }}">Workstations
                                Scrapped</a></li>
                        <li><a href="{{ route('History/Availability') }}"
                                class="{{ isMenuActive('History/Availability') }}">History
                                Workstation</a></li>
                        <li><a href="{{ route('legend') }}" class="{{ isMenuActive('legend') }}">Legend
                                Availability</a>
                        </li>
                    </ul>
                </li>

                {{-- Management User --}}
                <li>
                    <a href="#" class="dropdown-toggle {{ isMenuActive('indexResetPassswordIT') }}"
                        data-target="#management-user-menu">
                        <span><i class="fa fa-refresh fa-fw"></i> Management User</span>
                        <i class="fa fa-chevron-left fa-arrow"></i>
                    </a>
                    <ul class="nav nav-second-level collapse" id="management-user-menu">
                        <li><a href="{{ route('indexResetPassswordIT') }}"
                                class="{{ isMenuActive('indexResetPassswordIT') }}"><i class="fa fa-user fa-fw"></i>
                                Users</a></li>
                    </ul>
                </li>

                {{-- Register Access --}}
                <li>
                    <a href="#"
                        class="dropdown-toggle {{ isMenuActive(['it/registration/form/overtimes/index', 'form/overtime/progress/index', 'form/overtime/summary/index', 'it/form/history/user/index']) }}"
                        data-target="#register-access-menu">
                        <span><i class="fa fa-registered fa-fw"></i> Register Access</span>
                        <i class="fa fa-chevron-left fa-arrow"></i>
                    </a>
                    <ul class="nav nav-second-level collapse" id="register-access-menu">
                        <li><a href="{{ route('it/registration/form/overtimes/index') }}"
                                class="{{ isMenuActive('it/registration/form/overtimes/index') }}">List
                                Request Remote Access</a></li>
                        <li><a href="{{ route('form/overtime/progress/index') }}"
                                class="{{ isMenuActive('form/overtime/progress/index') }}">Form
                                is in progress</a></li>
                        <li><a href="{{ route('form/overtime/summary/index') }}"
                                class="{{ isMenuActive('form/overtime/summary/index') }}">Summary
                                Register</a></li>
                        @if (auth()->user()->id === 226)
                            <li><a href="{{ route('it/form/history/user/index') }}"
                                    class="{{ isMenuActive('it/form/history/user/index') }}">User
                                    of Duration Access</a></li>
                        @endif
                    </ul>
                </li>

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

                {{-- Asset Management --}}
                <li>
                    <a href="#" class="dropdown-toggle {{ isMenuActive(['asset-it', 'indexAssetPS']) }}"
                        data-target="#asset-management-menu">
                        <span><i class="glyphicon glyphicon-plus fa-fw"></i> Asset</span>
                        <i class="fa fa-chevron-left fa-arrow"></i>
                    </a>
                    <ul class="nav nav-second-level collapse" id="asset-management-menu">
                        <li><a href="{{ route('asset-it') }}" class="{{ isMenuActive('asset-it') }}">IT</a></li>
                        <li><a href="{{ route('indexAssetPS') }}"
                                class="{{ isMenuActive('indexAssetPS') }}">Production
                                Services</a></li>
                    </ul>
                </li>

                {{-- Inventory --}}
                <li>
                    <a href="#" class="dropdown-toggle {{ isMenuActive('indexUtamaAsset') }}"
                        data-target="#inventory-menu">
                        <span><i class="glyphicon glyphicon-plus fa-fw"></i> Inventory</span>
                        <i class="fa fa-chevron-left fa-arrow"></i>
                    </a>
                    <ul class="nav nav-second-level collapse" id="inventory-menu">
                        <li><a href="{{ route('indexUtamaAsset') }}"
                                class="{{ isMenuActive('indexUtamaAsset') }}">List
                                Inventory</a></li>
                    </ul>
                </li>
            @endif

            {{-- Pipeline WS Availability --}}
            @if (Auth::user()->level_hrd === 'Senior Pipeline' || Auth::user()->level_hrd === 'Technical Director')
                <li>
                    <a href="#"
                        class="dropdown-toggle {{ isMenuActive(['pipeline/workstations/availability/*', 'indexLegendAvailabilityPipeline']) }}"
                        data-target="#pipeline-ws-menu">
                        <span><i class="fa fa-home fa-fw"></i> WS Availability</span>
                        <i class="fa fa-chevron-left fa-arrow"></i>
                    </a>
                    <ul class="nav nav-second-level collapse" id="pipeline-ws-menu">
                        <li><a href="{{ route('pipeline/workstations/availability/index') }}"
                                class="{{ isMenuActive('pipeline/workstations/availability/index') }}">List
                                Workstations</a></li>
                        <li><a href="{{ route('pipeline/workstations/availability/idle/index') }}"
                                class="{{ isMenuActive('pipeline/workstations/availability/idle/index') }}">Workstations
                                Idle</a></li>
                        <li><a href="{{ route('pipeline/workstations/availability/fails/index') }}"
                                class="{{ isMenuActive('pipeline/workstations/availability/fails/index') }}">Workstations
                                Fails</a></li>
                        <li><a href="{{ route('pipeline/workstations/availability/scrapped/index') }}"
                                class="{{ isMenuActive('pipeline/workstations/availability/scrapped/index') }}">Workstations
                                Scrapped</a></li>
                        <li><a href="{{ route('indexLegendAvailabilityPipeline') }}"
                                class="{{ isMenuActive('indexLegendAvailabilityPipeline') }}">Legend
                                Availability</a></li>
                    </ul>
                </li>
            @endif

            {{-- Production Department WS Availability --}}
            @if (auth::user()->dept_category_id === 6 and auth::user()->hd === 1)
                <li>
                    <a href="#"
                        class="dropdown-toggle {{ isMenuActive(['pipeline/workstations/availability/*', 'indexLegendAvailabilityManager']) }}"
                        data-target="#production-ws-menu">
                        <span><i class="fa fa-home fa-fw"></i> WS Availability</span>
                        <i class="fa fa-chevron-left fa-arrow"></i>
                    </a>
                    <ul class="nav nav-second-level collapse" id="production-ws-menu">
                        <li><a href="{{ route('pipeline/workstations/availability/index') }}"
                                class="{{ isMenuActive('pipeline/workstations/availability/index') }}">List
                                Workstations</a></li>
                        <li><a href="{{ route('pipeline/workstations/availability/idle/index') }}"
                                class="{{ isMenuActive('pipeline/workstations/availability/idle/index') }}">Workstations
                                Idle</a></li>
                        <li><a href="{{ route('pipeline/workstations/availability/fails/index') }}"
                                class="{{ isMenuActive('pipeline/workstations/availability/fails/index') }}">Workstations
                                Fails</a></li>
                        <li><a href="{{ route('pipeline/workstations/availability/scrapped/index') }}"
                                class="{{ isMenuActive('pipeline/workstations/availability/scrapped/index') }}">Workstations
                                Scrapped</a></li>
                        <li><a href="{{ route('indexLegendAvailabilityManager') }}"
                                class="{{ isMenuActive('indexLegendAvailabilityManager') }}">Legend
                                Availability</a></li>
                    </ul>
                </li>
            @endif

            {{-- Stationery Management (HR) --}}
            @if (auth::user()->hr === 1)
                <li>
                    <a href="#"
                        class="dropdown-toggle {{ isMenuActive(['stationery/*', 'indexKategoryStationary']) }}"
                        data-target="#stationery-menu">
                        <span><i class="fa fa-pencil fa-fw"></i> Stationery</span>
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
            @endif

            {{-- HR Management Data Menu --}}
            @if (Auth::user()->hr === 1)
                <li>
                    <a href="#"
                        class="dropdown-toggle {{ isMenuActive(['hr_mgmt-data/*', 'End-Contract-Staff']) }}"
                        data-target="#hr-management-data-menu">
                        <span><i class="fa fa-cogs fa-fw"></i> Management Data<i>(hr)</i></span>
                        <i class="fa fa-chevron-left fa-arrow"></i>
                    </a>
                    <ul class="nav nav-second-level collapse" id="hr-management-data-menu">
                        <li><a href="{{ route('hr_mgmt-data/user') }}"
                                class="{{ isMenuActive('hr_mgmt-data/user') }}">Users</a></li>
                        <li><a href="{{ route('End-Contract-Staff') }}"
                                class="{{ isMenuActive('End-Contract-Staff') }}">Staff End
                                Contract</a></li>
                        <li><a href="{{ route('hr_mgmt-data/initial') }}"
                                class="{{ isMenuActive('hr_mgmt-data/initial') }}">Entitlement
                                Exdo Leave</a></li>
                        <li><a href="{{ route('hr_mgmt-data/department') }}"
                                class="{{ isMenuActive('hr_mgmt-data/department') }}">Department</a>
                        </li>
                        <li><a href="{{ route('hr_mgmt-data/project') }}"
                                class="{{ isMenuActive('hr_mgmt-data/project') }}">Project</a>
                        </li>
                        <li><a href="{{ route('hr_mgmt-data/previlege') }}"
                                class="{{ isMenuActive('hr_mgmt-data/previlege') }}">User
                                Previlege</a></li>
                    </ul>
                </li>
            @endif

            {{-- Admin Management Data Menu --}}
            @if (Auth::user()->admin === 1)
                <li>
                    <a href="#" class="dropdown-toggle {{ isMenuActive('mgmt-data/*') }}"
                        data-target="#admin-management-data-menu">
                        <span><i class="fa fa-cogs fa-fw"></i> Management Data<i>(HRD)</i></span>
                        <i class="fa fa-chevron-left fa-arrow"></i>
                    </a>
                    <ul class="nav nav-second-level collapse" id="admin-management-data-menu">
                        <li><a href="{{ route('mgmt-data/user') }}"
                                class="{{ isMenuActive('mgmt-data/user') }}">Users</a></li>
                        <li><a href="{{ route('mgmt-data/All_User') }}"
                                class="{{ isMenuActive('mgmt-data/All_User') }}">All Users</a>
                        </li>
                        <li><a href="{{ route('mgmt-data/department') }}"
                                class="{{ isMenuActive('mgmt-data/department') }}">Department</a>
                        </li>
                        <li><a href="{{ route('mgmt-data/previlege') }}"
                                class="{{ isMenuActive('mgmt-data/previlege') }}">User
                                Previlege</a></li>
                    </ul>
                </li>
            @endif

            {{-- Form Request Menu --}}
            <li>
                <a href="#" class="dropdown-toggle {{ isMenuActive('form/overtime/index') }}"
                    data-target="#form-request-menu">
                    <span><i class="fa fa-wpforms fa-fw"></i> Form Request</span>
                    <i class="fa fa-chevron-left fa-arrow"></i>
                </a>
                <ul class="nav nav-second-level collapse" id="form-request-menu">
                    <li><a href="{{ route('form/overtime/index') }}"
                            class="{{ isMenuActive('form/overtime/index') }}">Form Remote
                            Access Request VPN (WFH)</a></li>
                </ul>
            </li>

            {{-- Guideline Menu --}}
            <li>
                <a href="#"
                    class="dropdown-toggle {{ isMenuActive(['guided', 'esignature', 'guideline/*', 'production/phonebook', 'guideline/wfh']) }}"
                    data-target="#guideline-menu">
                    <span><i class="fa fa-wpforms fa-fw"></i> Guideline</span>
                    <i class="fa fa-chevron-left fa-arrow"></i>
                </a>
                <ul class="nav nav-second-level collapse" id="guideline-menu">
                    <li><a href="{{ route('guided') }}" class="{{ isMenuActive('guided') }}">Booklet</a></li>
                    <li><a href="{{ route('esignature') }}" class="{{ isMenuActive('esignature') }}">IFW Mail
                            Signature</a>
                    </li>
                    <li><a href="{{ route('guideline/induction') }}"
                            class="{{ isMenuActive('guideline/induction') }}">Induction</a>
                    </li>
                    <li><a href="{{ route('guideline/orginazation') }}"
                            class="{{ isMenuActive('guideline/orginazation') }}">Organization
                            Chart</a></li>
                    <li><a href="{{ route('production/phonebook') }}"
                            class="{{ isMenuActive('production/phonebook') }}">Phone Book</a>
                    </li>
                    <li><a href="https://3.basecamp.com/4952258/buckets/20262700/message_boards/7482724197"
                            target="_blank" rel="noopener noreferrer">Wiki</a></li>
                    <li><a href="{{ route('guideline/wfh') }}" class="{{ isMenuActive('guideline/wfh') }}">WFH</a>
                    </li>
                </ul>
            </li>

            {{-- Organizational Chart --}}
            <li>
                <a href="{{ route('structute-organitation') }}"
                    class="{{ isMenuActive('structute-organitation') }}">
                    <i class="fa fa-sitemap fa-fw"></i> Organizational Chart
                </a>
            </li>

            {{-- Troubleshooting Guidelines --}}
            <li>
                <a href="{{ route('guidelines') }}" class="{{ isMenuActive('guidelines') }}">
                    <i class="fa fa-book fa-fw"></i> Troubleshooting Guidelines (WFH)
                </a>
            </li>

            {{-- Event Menu --}}
            <li>
                <a href="#"
                    class="dropdown-toggle {{ isMenuActive(['infiniteVirRun/index', 'infiniteVirRun/*']) }}"
                    data-target="#event-menu">
                    <span><i class="fa fa-flag-checkered fa-fw"></i> Event</span>
                    <i class="fa fa-chevron-left fa-arrow"></i>
                </a>
                <ul class="nav nav-second-level collapse" id="event-menu">
                    <li>
                        <a href="#"
                            class="dropdown-toggle {{ isMenuActive(['infiniteVirRun/index', 'infiniteVirRun/*']) }}"
                            data-target="#infinite-virtual-run-menu">
                            <span><i class="fa fa-certificate fa-fw"></i> Infinite Virtual Run</span>
                            <i class="fa fa-chevron-left fa-arrow"></i>
                        </a>
                        <ul class="nav nav-second-level collapse" id="infinite-virtual-run-menu">
                            <li><a href="{{ route('infiniteVirRun/index') }}"
                                    class="{{ isMenuActive('infiniteVirRun/index') }}">2025</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>

            {{-- Logout --}}
            <li>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out fa-fw"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</nav>
