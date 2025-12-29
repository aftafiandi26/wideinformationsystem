<li>
    <a href="#" class="dropdown-toggle {{ isMenuActive(['infiniteVirRun/index', 'infiniteVirRun/*']) }}"
        data-target="#event-menu">
        <span><i class="fa fa-flag-checkered fa-fw"></i> Event</span>
        <i class="fa fa-chevron-left fa-arrow"></i>
    </a>
    <ul class="nav nav-second-level collapse" id="event-menu">
        <li>
            <a href="#" class="dropdown-toggle {{ isMenuActive(['infiniteVirRun/index', 'infiniteVirRun/*']) }}"
                data-target="#infinite-virtual-run-menu">
                <span>Infinite Virtual Run</span>
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
