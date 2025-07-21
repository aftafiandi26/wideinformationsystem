@extends('layout')

@section('title')
    Infinite Virtual Run
@stop

@section('top')
    @include('assets_css_1')
    @include('assets_css_2')
    @include('assets_css_3')
    @include('assets_css_4')
@stop

@section('navbar')
    @include('navbar_top')
    @include('navbar_left', [
        'c2' => 'active',
    ])
@stop

@push('style')
    @include('all_employee.Event.InfiniteVirRun.virCCS')
    <style>
        .badge-success {
            background-color: green;
        }

        .badge-danger {
            background-color: red;
        }
    </style>
@endpush
@section('body')

    @include('all_employee.Event.InfiniteVirRun.menuVirRun')

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <p class="text-bold">Participant History Activities</p>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-condensed table-striped table-hover" width="100%"
                        id="tables">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>EBIB</th>
                                <th>Participant</th>
                                <th>Distance <sup>(Km)</sup></th>
                                <th>Moving Time <sup>(HH:MM:SS)</sup></th>
                                <th>Strava URL</th>
                                <th>Status</th>
                                <th>Submission Date</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>

@stop
@section('bottom')
    @include('assets_script_1')
    @include('assets_script_2')
    @include('assets_script_3')
    @include('assets_script_7')
@stop
@push('js')
    <script src="{{ asset('assets/js/datetimepicker/jquery.datetimepicker.full.js') }}"></script>
    <script>
        $(document).ready(function() {
            var element = document.getElementById('histori');
            element.classList.add('press');

            $('table#tables').DataTable({
                processing: true,
                responsive: true,
                ajax: "{{ route('admin/infinite-virtual-run/history/data') }}",
                columns: [{
                        data: 'DT_Row_Index',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'ebib'
                    }, {
                        data: 'fullname'
                    }, {
                        data: 'distance'
                    }, {
                        data: 'mvtime'
                    }, {
                        data: 'stravaURL',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'verify',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'created_at',
                        searchable: false
                    }

                ],
                dom: 'lBfrtip',
                buttons: [
                    'excel'
                ],
            });
        });
    </script>
@endpush
