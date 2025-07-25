<div class="sidebar-nav navbar-collapse ">
    <ul class="nav" id="side-menu">
        <li>
            @if (Auth::user()->prof_pict === null)
                <a style="color: black">
                    <i>&nbsp&nbsp Hi, <b>{!! Auth::user()->first_name !!}</b></i>
            @endif

            @if (Auth::user()->prof_pict !== null)
                <a style="color: black">
                    <img src="{{ asset('storage/app/prof_pict/' . Auth::user()->prof_pict . '') }}" class="img-circle"
                        style="width: 50px; height: 50px;" alt="img">
                    <i>&nbsp&nbsp Hi, <b>{!! Auth::user()->first_name !!}</b></i>
            @endif
            </a>
        </li>
        <li>
            <a class="{!! $c2 or '' !!}" href="#"><i class="fa fa-fw fa-file-text-o"></i> Infinite Virtual
                Run
                <span class="fa arrow"></span></a>
            <ul class="nav nav-second-level {!! $c1u or '' !!}">
                <li>
                    <a class="{!! $c16 or '' !!}" href="{{ route('infiniteVirRun/index') }}"><i
                            class="fa fa-fw fa fa-genderless"></i>
                        {{ date('Y') }}</a>
                </li>
        </li>
    </ul>
</div>
