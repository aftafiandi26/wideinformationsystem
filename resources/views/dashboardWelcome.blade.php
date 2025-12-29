@extends('zLayoutNew.layout1')

@section('title')
    General Dashboard
@stop

@section('top')
    @include('zLayoutNew.fundamental.asset_css_1')
    @include('zLayoutNew.fundamental.asset_css_2')
@stop

@section('navbar')
    @include('zLayoutNew.navbar')
@stop

@section('sidebar')
    @include('zLayoutNew.sidebar')
@stop

@push('style')
    <style>
        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.8;
            }

            50% {
                transform: scale(1.1);
                opacity: 1;
            }
        }

        @keyframes fadeInOut {

            0%,
            100% {
                opacity: 0.6;
            }

            50% {
                opacity: 1;
            }
        }

        .bg-img {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            width: 110% !important;
            height: 100% !important;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            margin: 0;
        }

        /* Desktop: Banner_InfiniteStudios.png as background */
        @media (min-width: 769px) {
            .bg-img {
                background-image: url('{{ asset('assets/Banner_InfiniteStudios.png') }}');
                min-height: 100vh;
                height: 100vh;
                border-radius: 0;
                box-shadow: none;
                margin: 0;
                padding: 0;
                background-attachment: fixed;
                background-size: cover;
                background-position: center;
                position: relative;
                width: 100%;
            }

            /* Ensure full body coverage on desktop */
            body {
                margin: 0;
                padding: 0;
                overflow-x: hidden;
            }

            .container-fluid.bg-img {
                padding: 0 !important;
                margin-left: -15vh !important;
            }
        }

        /* Mobile: server_icon1.jpg as background */
        @media (max-width: 768px) {
            .bg-img {
                background-image: url('{{ asset('assets/server_icon1.jpg') }}');
                min-height: 100vh;
                height: auto;
                border-radius: 0;
                box-shadow: none;
                margin: 0;
                background-size: cover;
                background-position: center;
            }
        }

        /* Small mobile optimization */
        @media (max-width: 480px) {
            .bg-img {
                min-height: 100vh;
                height: auto;
                border-radius: 0;
                box-shadow: none;
                margin: 0;
                background-size: cover;
                background-position: center;
            }
        }

        /* Hide the img element since we're using background */
        .img-infinite-studio {
            display: none;
        }
    </style>
@endpush

@section('body')
    <div class="container-fluid bg-img"></div>
@stop

@section('bottom')
    @include('zLayoutNew.fundamental.asset_script_1')
    @include('zLayoutNew.fundamental.asset_script_2')
@stop

@push('js')
    <script>
        $(document).ready(function() {

        });
    </script>
@endpush
