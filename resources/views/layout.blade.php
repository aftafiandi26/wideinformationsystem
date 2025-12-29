<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="{{ asset('assets/WIS_LOGO_2025_V2.png') }}">
    <title>@yield('title') - WIS</title>
    @yield('top')
    <style>
        .grogol li a:hover {
            color: red;
        }

        a#undermaintanance {
            color: rgb(196, 194, 194);
        }
    </style>
    @stack('style')
</head>

<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0;">
            @yield('navbar')
        </nav>

        @if (Session::get('getError') || Session::has('message') || Session::has('success') || Session::has('reminder'))
            <div id="page-wrapper" style="padding-top: 10px;">
            @else
                <div id="page-wrapper">
        @endif
        @if (Session::get('getError'))
            <div class="alert alert-danger alert-dismissable fade in" style="margin-bottom: 10px;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {!! Session::get('getError') !!}
            </div>
        @endif

        @if (Session::has('message'))
            <div class="alert alert-info alert-dismissable fade in" style="margin-bottom: 10px;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {!! Session::get('message') !!}
            </div>
        @endif

        @if (Session::has('success'))
            <div class="alert alert-success alert-dismissable fade in" style="margin-bottom: 10px;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {!! Session::get('success') !!}
            </div>
        @endif

        @if (Session::has('reminder'))
            <div class="alert alert-warning alert-dismissable fade in" style="margin-bottom: 10px;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {!! Session::get('reminder') !!}
            </div>
        @endif

        @yield('body')

    </div>
    </div>

    @yield('bottom')

    <script>
        $(document).ready(function() {
            @yield('script')
        });
        $('a#undermaintanance').on('click', function() {
            var param = $(this).attr('title');
            var time = $(this).attr('time');

            alert('Sorry, ' + param + ' under maintenance !!');
            alert('You can access it at ' + time);
        });
    </script>

    @stack('js')
</body>

</html>
