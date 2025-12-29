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
