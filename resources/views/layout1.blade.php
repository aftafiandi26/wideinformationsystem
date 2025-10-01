<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>@yield('title') - WIS</title>

    @yield('top')

    <link rel="stylesheet" href="{{ asset('assets/resources/css/layout1.css') }}" type="text/css">
    <script src="{{ asset('assets/resources/js/layout1.js') }}"></script>

    <!-- Force Override Bootstrap dengan CSS Inline -->
    <style>
        /* Force override Bootstrap dengan specificity tinggi */
        body .content-wrapper {
            background: #ffffff !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08) !important;
            margin: 10px !important;
            position: relative !important;
            overflow: hidden !important;
        }

        body .container {
            background: transparent !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        body .row {
            background: transparent !important;
            margin: 0 !important;
        }

        body .col-lg-12 {
            background: transparent !important;
            padding: 0 !important;
        }

        /* Force sidebar styling */
        body .sidebar {
            background: linear-gradient(135deg, #3298f8 0%, #b3cbf3 100%) !important;
            position: fixed !important;
        }

        /* Force navbar styling */
        body .navbar-top {
            background: linear-gradient(135deg, #54a5f1 0%, #b3cbf3 100%) !important;
            position: fixed !important;
        }

        /* Ensure content is visible on desktop */
        body .main-content {
            margin-left: 250px !important;
            padding-top: 80px !important;
            position: relative !important;
            z-index: 1 !important;
        }

        body .content-wrapper {
            position: relative !important;
            z-index: 1 !important;
            background: #ffffff !important;
            min-height: 200px !important;
        }

        body .container {
            position: relative !important;
            z-index: 1 !important;
        }

        body .row {
            position: relative !important;
            z-index: 1 !important;
        }

        body .col-lg-12 {
            position: relative !important;
            z-index: 1 !important;
            background: transparent !important;
            color: #000000 !important;
            font-size: 16px !important;
            padding: 20px !important;
        }

        /* Mobile-specific fixes */
        @media (max-width: 768px) {
            body .main-content {
                margin-left: 0 !important;
                padding-top: 80px !important;
            }

            body .content-wrapper {
                margin: 5px !important;
                padding: 15px !important;
                border-radius: 8px !important;
            }

            body .container {
                padding: 0 !important;
                margin: 0 !important;
            }

            body .row {
                margin: 0 !important;
            }

            body .col-lg-12 {
                padding: 15px !important;
                margin: 0 !important;
                background: #ffffff !important;
                border-radius: 8px !important;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
                position: relative !important;
                z-index: 1 !important;
            }
        }
    </style>

    @stack('style')
</head>

<body>
    <div id="wrapper">
        <!-- Sidebar | Navbar -->
        @yield('navbar')

        <!-- Sidebar Overlay for Mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Main Content -->

        <div id="mainContent" class="main-content">

            <!-- Session Alerts Container -->
            @include('zLayoutNew.alerts')

            <!-- Main Content Wrapper -->
            <div class="content-wrapper">
                @yield('body')
            </div>

        </div>
</body>

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

</html>
