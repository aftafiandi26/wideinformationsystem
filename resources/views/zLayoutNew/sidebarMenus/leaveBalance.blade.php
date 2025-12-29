@if (auth::user()->dept_category_id === 6)
    @if (auth()->user()->koor == true ||
            auth()->user()->pm == true ||
            auth()->user()->producer == true ||
            auth()->user()->hd == true)
        <li>
            <a href="{{ route('coordinator/leave-balance/index') }}"
                class="{{ isMenuActive('coordinator/leave-balance/index') }}">Leave Balance</a>
        </li>
    @endif
@else
    @if (auth()->user()->hr == false)
        <li>
            <a href="{{ route('coordinator/leave-balance/index') }}"
                class="{{ isMenuActive('coordinator/leave-balance/index') }}">Leave Balance</a>
        </li>
    @endif
@endif
