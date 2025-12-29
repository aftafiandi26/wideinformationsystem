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
            class="dropdown-toggle {{ isMenuActive(['leave/*', 'all_employes/leave/*', 'employee/forfeited/*', 'Koordinator/*', 'Supervisor/*', 'ProjectManager/*', 'leave/HD_approval', 'head-of-approval/*', 'index-Grafik', 'index-History', 'leave/GM_approval', 'leave/alltransaction', 'leave/calender/*', 'leave/HRD_approval', 'indexAllEmployee', 'indexApprovalInfinite', 'leave/HR_ver', 'indexSummaryVerified', 'PipeLineTechnicalIndexApproval', 'facilities/admin/verify', 'indexUnpaidLeave']) }}"
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

            @include('zLayoutNew.sidebarMenus.leaveBalance')

            @if (auth::user()->level_hrd === 'Payroll')
                <li><a href="{{ route('indexUnpaidLeave') }}" class="{{ isMenuActive('indexUnpaidLeave') }}">Unpaid
                        Employee</a></li>
            @endif

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
                    <a href="{{ route('leave/HD_approval') }}" class="{{ isMenuActive('leave/HD_approval') }}">
                        Leave Approval (Head of Deparment)
                    </a>
                </li>
                @if (auth::user()->dept_ApprovedHOD === 1)
                    <li><a href="{{ route('head-of-approval/index') }}"
                            class="{{ isMenuActive('head-of-approval/index') }}"> Leave Approval (Head of
                            Deparment)
                @endif
                @if (Auth::user()->dept_category_id === 6)
                    <li><a href="{{ route('index-Grafik') }}" class="{{ isMenuActive('index-Grafik') }}">Summary of
                            Leave</a></li>
                @endif
                <li><a href="{{ route('index-History') }}" class="{{ isMenuActive('index-History') }}">History of
                        Leave</a></li>
            @endif

            {{-- General Manager Leave Approval --}}
            @if (Auth::user()->gm === 1)
                <li><a href="{{ route('leave/GM_approval') }}" class="{{ isMenuActive('leave/GM_approval') }}">Leave
                        Approval (GM)</a></li>
                <li><a href="{{ route('leave/alltransaction') }}"
                        class="{{ isMenuActive('leave/alltransaction') }}">All Leave Transaction (GM)</a>
                </li>
            @endif

            {{-- HRD Leave Approval --}}
            @if (Auth::user()->hrd === 1)
                <li><a href="{{ route('leave/HRD_approval') }}" class="{{ isMenuActive('leave/HRD_approval') }}">Leave
                        Approval For Head of
                        Department></li>
                <li><a href="{{ route('indexAllEmployee') }}" class="{{ isMenuActive('indexAllEmployee') }}">Leave
                        Approval Employee
                        Verification</a></li>
            @endif

            {{-- Infinite Leave Approval --}}
            @if (Auth::user()->infiniteApprove === 1)
                <li><a href="{{ route('indexApprovalInfinite') }}"
                        class="{{ isMenuActive('indexApprovalInfinite') }}">Kinema Leave Approval</a></li>
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
                        Approval (beta)</a></li>
            @endif

            {{-- Facility Admin Leave Summary --}}
            @if (Auth::user()->dept_category_id == 5 and Auth::user()->position == 'Admin Facility')
                <li><a href="{{ route('facilities/admin/verify') }}"
                        class="{{ isMenuActive('facilities/admin/verify') }}">Summary Leave (Facility)</a>
                </li>
            @endif
        </ul>
    </li>
@endif
