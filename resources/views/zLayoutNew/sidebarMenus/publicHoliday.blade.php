@if (auth::user()->dept_category_id === 3)
    <li>
        <a href="#"
            class="dropdown-toggle {{ isMenuActive(['indexAllSummary', 'indexUnpaidLeave', 'indexViewOffYears']) }}"
            data-target="#public-holiday-menu">
            <span><i class="fa fa-soundcloud fa-fw"></i> Public Holiday</span>
            <i class="fa fa-chevron-left fa-arrow"></i>
        </a>
        <ul class="nav nav-second-level collapse" id="public-holiday-menu">
            <li><a href="{{ route('indexAllSummary') }}" class="{{ isMenuActive('indexAllSummary') }}">All
                    of Summary</a>
            </li>
            @if (auth::user()->hr === 1)
                <li><a href="{{ route('indexViewOffYears') }}" class="{{ isMenuActive('indexViewOffYears') }}">Public
                        Holiday</a></li>
            @endif
        </ul>
    </li>
@endif
