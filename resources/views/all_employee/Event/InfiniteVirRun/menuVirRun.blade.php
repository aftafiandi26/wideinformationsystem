<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Welcome to Infinite Virtual Run</h1>
    </div>
</div>
<div class="row mb-3">
    <div class="col-lg-12">
        <a class="btn btn-sm btn-default" href="{{ route('infiniteVirRun/index') }}" id="leadMen">Leaderboard</a>
        @if ($data)
            <a class="btn btn-sm btn-default" href="{{ route('infiniteVirRun/submission/list') }}"
                id="listed">Activities</a>
            <a class="btn btn-sm btn-default" href="{{ route('infiniteVirRun/certificate', auth()->user()->id) }}"
                id="cert">e-Certificate</a>
            <a class="btn btn-sm btn-default" href="{{ route('infiniteVirRun/submission') }}"
                id="submission">Submission</a>
        @else
            <a class="btn btn-sm btn-default" data-role="{{ route('infiniteVirRun/register') }}" data-toggle="modal"
                data-target="#virRegister" id="virPressRegister">Register</a>
        @endif
        @if ($admin)
            <a class="btn btn-sm btn-default" href="{{ route('admin/infinite-virtual-run/verify') }}"
                id="verification">Verification</a>
            <a class="btn btn-sm btn-default" id="participant"
                href="{{ route('admin/infinite-virtual-run/participant') }}">Participant</a>
            <a class="btn btn-sm btn-default" id="histori"
                href="{{ route('admin/infinite-virtual-run/history') }}">History</a>
        @endif
    </div>
</div>
