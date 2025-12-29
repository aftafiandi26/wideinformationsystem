@if (auth()->user()->dept_category_id === 1 || (auth()->user()->dept_category_id === 10 && auth()->user()->hd == true))
    {{-- WS Availability --}}
    <li>
        <a href="#"
            class="dropdown-toggle {{ isMenuActive(['workstations/availability/*', 'History/Availability', 'legend']) }}"
            data-target="#ws-availability-menu">
            <span><i class="fas fa-laptop fa-fw"></i> Workstation Availability</span>
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
            <span><i class="fa fa-user fa-fw"></i> Management User</span>
            <i class="fa fa-chevron-left fa-arrow"></i>
        </a>
        <ul class="nav nav-second-level collapse" id="management-user-menu">
            <li><a href="{{ route('indexResetPassswordIT') }}" class="{{ isMenuActive('indexResetPassswordIT') }}"><i
                        class="fa fa-user fa-fw"></i>
                    Users</a></li>
        </ul>
    </li>

    {{-- Register Access --}}
    <li>
        <a href="#"
            class="dropdown-toggle {{ isMenuActive(['it/registration/form/overtimes/index', 'form/overtime/progress/index', 'form/overtime/summary/index', 'it/form/history/user/index']) }}"
            data-target="#register-access-menu">
            <span><i class="far fa-registered fa-fw"></i> Register Access</span>
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
    {{-- Menu for developer --}}
    @include('zLayoutNew.sidebarMenus.devMenus')

    {{-- Asset Management --}}

    {{-- Inventory --}}
    <li>
        <a href="#" class="dropdown-toggle {{ isMenuActive('indexUtamaAsset') }}" data-target="#inventory-menu">
            <span><i class="glyphicon glyphicon-plus fa-fw"></i> Inventory</span>
            <i class="fa fa-chevron-left fa-arrow"></i>
        </a>
        <ul class="nav nav-second-level collapse" id="inventory-menu">
            <li><a href="{{ route('indexUtamaAsset') }}" class="{{ isMenuActive('indexUtamaAsset') }}">List
                    Inventory</a></li>
        </ul>
    </li>

    <li>
        <a href="#" class="dropdown-toggle {{ isMenuActive(['asset-it', 'indexAssetPS']) }}"
            data-target="#it-werehouse-menu">
            <span><i class="fab fa-bitbucket fa-fw"></i> Werehouse</span>
            <i class="fa fa-chevron-left fa-arrow"></i>
        </a>
        <ul class="nav nav-second-level collapse" id="it-werehouse-menu">
            <li>
                <a href="#" class="dropdown-toggle {{ isMenuActive(['asset-it', 'indexAssetPS']) }}"
                    data-target="#it-werehouse-menu-asset">
                    <span>Asset</span> <i class="fa fa-chevron-left fa-arrow"></i>
                </a>
                <ul class="nav nav-second-level collapse">
                    <li><a href="{{ route('asset-it') }}" class="{{ isMenuActive('asset-it') }}">IT</a></li>
                    <li><a href="{{ route('indexAssetPS') }}" class="{{ isMenuActive('indexAssetPS') }}">Production
                            Services</a></li>
                </ul>
            </li>
        </ul>
    </li>
@endif
