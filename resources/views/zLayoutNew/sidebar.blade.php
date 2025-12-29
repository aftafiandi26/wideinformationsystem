@php
    // Helper function untuk active class
    function isMenuActive($routes)
    {
        if (is_string($routes)) {
            return request()->routeIs($routes);
        }
        foreach ($routes as $route) {
            if (request()->routeIs($route)) {
                return 'active';
            }
        }
        return '';
    }
@endphp

<nav id="sidebar" class="sidebar">
    <div class="logo-container">
        <a href="{{ route('index') }}">
            <img src="{{ asset('assets/WIS_LOGO_2025_V2.png') }}" alt="Logo" class="img-responsive">
        </a>
    </div>

    <div class="user-profile">
        @if (Auth::user()->prof_pict === null)
            <div class="user-name">
                <i class="fa fa-user-circle fa-3x" style="color: #ecf0f1;"></i><br>
                <small>Hi, <b>{{ Auth::user()->first_name }}</b></small>
            </div>
        @else
            <img src="https://picsum.photos/seed/picsum/200/300" class="img-circle" alt="Profile Picture">
            <div class="user-name">
                <small>Hi, <b>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</b></small>
            </div>
        @endif
    </div>
    {{-- Simple Search - Desktop Only --}}
    <div class="sidebar-search">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search menu..." id="sidebarSearch">
        </div>
    </div>
    {{-- Sidebar Menu Container --}}
    <div class="sidebar-menu-container">
        <ul class="nav" id="side-menu">

            {{-- Dashboard --}}
            <li>
                <a href="{{ route('index') }}" class="{{ isMenuActive('index') }}">
                    <i class="fas fa-tachometer-alt fa-fw"></i> Dashboard
                </a>
            </li>

            {{-- Leave Management Menu - Leave Balance Menu --}}
            @include('zLayoutNew.sidebarMenus.leave')

            {{-- Attendance Menu --}}
            @include('zLayoutNew.sidebarMenus.attendance')

            {{-- Leave Reports (HR Department) --}}
            @include('zLayoutNew.sidebarMenus.leaveHRDepartment')

            {{-- HR Management Data Menu (HR - Admin) --}}
            @include('zLayoutNew.sidebarMenus.HR_Admin')

            {{-- IT Department Menu --}}
            @include('zLayoutNew.sidebarMenus.ITDepartment')

            {{-- Pipeline WS Availability --}}
            @include('zLayoutNew.sidebarMenus.pipeline')

            {{-- Production Department WS Availability --}}
            @include('zLayoutNew.sidebarMenus.productionWSAvailability')

            {{-- Prodcutiion Technology Menu --}}
            @include('zLayoutNew.sidebarMenus.productionTechnology')

            {{-- Admin Management Data Menu --}}
            @include('zLayoutNew.sidebarMenus.administrator')

            {{-- Form Request Menu --}}
            @include('zLayoutNew.sidebarMenus.formRequest')

            {{-- Guideline Menu | Organizational Chart | Troubleshooting Guidelines --}}
            @include('zLayoutNew.sidebarMenus.guideline')

            {{-- Event Menu --}}
            @include('zLayoutNew.sidebarMenus.eventVirtual')
        </ul>

    </div>
</nav>
