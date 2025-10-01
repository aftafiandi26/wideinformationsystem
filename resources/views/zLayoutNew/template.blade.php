@extends('zLayoutNew.layout1')

@section('title')
    title
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
@endpush

@section('body')
    {{-- isi disini --}}

@stop

@section('bottom')
    @include('zLayoutNew.fundamental.asset_script_1')
    @include('zLayoutNew.fundamental.asset_script_2')
@stop

@push('js')
    <script>
        $(document).ready(function() {
            $(window).on('load', function() {
                if ($("#preloader").length) {
                    $("#preloader").css({
                        'opacity': '0',
                        'visibility': 'hidden'
                    });

                    setTimeout(function() {
                        $("#preloader").remove();
                    }, 500);
                }
            });
        });
    </script>
@endpush
