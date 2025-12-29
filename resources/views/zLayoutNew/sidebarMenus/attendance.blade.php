@if (auth()->user()->hd == false && auth()->user()->gm == false)
    <li>
        <a href="{{ route('attendance/index') }}" class="{{ isMenuActive('attendance/index') }}">
            <i class="fa fa-bar-chart fa-fw"></i> Attendance
        </a>
    </li>
@endif

@if (auth()->user()->hd == true || auth()->user()->gm == true)
    <li>
        <a href="#" class="dropdown-toggle {{ isMenuActive(['attendance/index', 'hod/attendance/summary']) }}"
            data-target="#attendance-menu">
            <span><i class="fa fa-soundcloud fa-fw"></i> Attendance</span>
            <i class="fa fa-chevron-left fa-arrow"></i>
        </a>
        <ul class="nav nav-second-level collapse" id="attendance-menu">
            <li><a href="{{ route('attendance/index') }}" class="{{ isMenuActive('attendance/index') }}">Attendance</a>
            </li>
            <li><a href="{{ route('hod/attendance/summary') }}"
                    class="{{ isMenuActive('hod/attendance/summary') }}">Summary</a>
            </li>
        </ul>
    </li>
@endif
