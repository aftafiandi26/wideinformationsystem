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
        .panel-heading {
            font-weight: bold;
        }
    </style>
@endpush
@section('body')

    @include('all_employee.Event.InfiniteVirRun.menuVirRun')

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">Verifying Submission Participants</div>
                <div class="panel-body">
                    <table class="table table-bordered table-hover table-striped table-condensed" width="100%"
                        id="tables">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>EBIB</th>
                                <th>Distance <sup>(Km)</sup></th>
                                <th>Moving Time <sup>(HH:MM:SS)</sup></th>
                                <th>Strava URL</th>
                                <th>Submission Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div id="modalVerify" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" id="popUpVerifying">

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
    <script>
        $(document).ready(function() {
            var element = document.getElementById('verification');
            element.classList.add('press');

            $('table#tables').DataTable({
                processing: true,
                responsive: true,
                ajax: "{{ route('admin/infinite-virtual-run/verify/data') }}",
                columns: [{
                        data: 'DT_Row_Index',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'fullname'
                    }, {
                        data: 'ebib'
                    }, {
                        data: 'distance'
                    }, {
                        data: 'mvtime'
                    }, {
                        data: 'stravaURL'
                    }, {
                        data: 'created_at',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'actions',
                        orderable: false,
                        searchable: false
                    },

                ],
                dom: 'lBfrtip',
                buttons: [
                    'excel'
                ],
            });

            $(document).on('click', 'a#verify', function(e) {
                var id = $(this).attr('data-role');
                $.ajax({
                    url: id,
                    success: function(e) {
                        $("div#popUpVerifying").html(e);
                    }
                });
            });
        });
    </script>
@endpush
