<li>
    <a href="#"
        class="dropdown-toggle {{ isMenuActive(['guided', 'esignature', 'guideline/*', 'production/phonebook', 'guideline/wfh', 'Guidelines', 'structute-organitation']) }}"
        data-target="#guideline-menu">
        <span><i class="fa fa-wpforms fa-fw"></i> Guideline</span>
        <i class="fa fa-chevron-left fa-arrow"></i>
    </a>
    <ul class="nav nav-second-level collapse" id="guideline-menu">
        <li><a href="{{ route('guided') }}" class="{{ isMenuActive('guided') }}">Booklet</a></li>
        <li><a href="{{ route('esignature') }}" class="{{ isMenuActive('esignature') }}">IFW Mail
                Signature</a>
        </li>
        <li><a href="{{ route('guideline/induction') }}" class="{{ isMenuActive('guideline/induction') }}">Induction</a>
        </li>
        <li><a href="{{ route('guideline/orginazation') }}"
                class="{{ isMenuActive('guideline/orginazation') }}">Organization
                Chart</a></li>
        <li><a href="{{ route('production/phonebook') }}" class="{{ isMenuActive('production/phonebook') }}">Phone
                Book</a>
        </li>
        <li><a href="https://3.basecamp.com/4952258/buckets/20262700/message_boards/7482724197" target="_blank"
                rel="noopener noreferrer">Wiki</a></li>
        <li><a href="{{ route('guideline/wfh') }}" class="{{ isMenuActive('guideline/wfh') }}">WFH</a>
        </li>
        <li>
            <a href="{{ route('guidelines') }}" class="{{ isMenuActive('guidelines') }}">
                Troubleshooting Guidelines (WFH)
            </a>
        </li>
        <li>
            <a href="{{ route('structute-organitation') }}" class="{{ isMenuActive('structute-organitation') }}">
                Organizational Chart
            </a>
        </li>
    </ul>
</li>
