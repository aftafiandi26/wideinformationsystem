@if (Session::get('getError') || Session::has('message') || Session::has('success') || Session::has('reminder'))
    <div class="alerts-container">
        @if (Session::get('getError'))
            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                <i class="fa fa-exclamation-triangle alert-icon"></i>
                <span class="alert-text">{!! Session::get('getError') !!}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (Session::has('message'))
            <div class="alert alert-info alert-dismissible fade in" role="alert">
                <i class="fa fa-info-circle alert-icon"></i>
                <span class="alert-text">{!! Session::get('message') !!}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade in" role="alert">
                <i class="fa fa-check-circle alert-icon"></i>
                <span class="alert-text">{!! Session::get('success') !!}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (Session::has('reminder'))
            <div class="alert alert-warning alert-dismissible fade in" role="alert">
                <i class="fa fa-exclamation-circle alert-icon"></i>
                <span class="alert-text">{!! Session::get('reminder') !!}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>
@endif
