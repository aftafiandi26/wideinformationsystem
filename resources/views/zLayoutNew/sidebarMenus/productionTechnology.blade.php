@if (auth()->user()->dept_category_id === 1 or auth()->user()->dept_category_id === 10)
    <li>
        <a href="#" class="dropdown-toggle {{ isMenuActive(['prodTech/manage/vpn/list']) }}"
            data-target="#production-technology-menu">
            <span><i class="fa fa-pencil fa-fw"></i> Production Technology</span>
            <i class="fa fa-chevron-left fa-arrow"></i>
        </a>
        <ul class="nav nav-second-level collapse" id="production-technology-menu">
            <li>
                <a href="#" class="dropdown-toggle {{ isMenuActive(['']) }}"
                    data-target="#production-technology-menu-vpn-overtime">
                    <span>VPN Overtime</span>
                    <i class="fa fa-chevron-left fa-arrow"></i>
                </a>
                <ul class="nav nav-third-level collapse" id="production-technology-menu-vpn-overtime">
                    <li>
                        <a href="" class="{{ isMenuActive('prodTech/manage/vpn/list') }}">Employes List VPN</a>
                    </li>

                </ul>
            </li>
        </ul>
    </li>
@endif
