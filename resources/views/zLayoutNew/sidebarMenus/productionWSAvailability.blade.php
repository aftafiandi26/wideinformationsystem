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
