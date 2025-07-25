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

        table.dataTable tr.top-rank,
        table.dataTable tr.top-rank td {
            background-color: gold !important;
            color: black !important;
        }

        table.dataTable tr.winner,
        table.dataTable tr.winner td {
            background-color: #E5E4E2 !important;
            color: black !important;
        }

        table.dataTable tr.runner-up,
        table.dataTable tr.runner-up td {
            background-color: #CD7F32 !important;
            color: black !important;
        }
    </style>
@endpush
@section('body')

    @include('all_employee.Event.InfiniteVirRun.menuVirRun')

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">Leaderboard Male</div>
                <div class="panel-body">
                    <table class="table table-bordered table-hover table-striped table-condensed" width="100%"
                        id="tableMale">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Name</th>
                                <th>EBIB</th>
                                <th>Entity</th>
                                <th>Distance <sup>(Km)</sup> </th>
                                <th>Duration</th>
                                <th>Total Activities</th>
                                <th>Progress</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">Leaderboard Female</div>
                <div class="panel-body">
                    <table class="table table-bordered table-hover table-striped table-condensed" width="100%"
                        id="tableFemale">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Name</th>
                                <th>EBIB</th>
                                <th>Entity</th>
                                <th>Distance <sup>(Km)</sup> </th>
                                <th>Duration</th>
                                <th>Total Activities</th>
                                <th>Progress</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="virRegister" class="modal fade" role="dialog">
        <div class="modal-dialog" id="modalRegister">

        </div>
    </div>

    <div id="submission" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" id="contentSubmission">

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
            var element = document.getElementById('leadMen');
            element.classList.add('press');

            $("a#virPressRegister").on('click', function() {
                let url = $(this).attr('data-role');

                $.ajax({
                    url: url,
                    success: function(e) {
                        $("#modalRegister").html(e);
                    }
                });
            });

            $('table#tableMale').DataTable({
                processing: true,
                responsive: true,
                ajax: "{{ route('infiniteVirRun/data/male') }}",
                columnDefs: [{
                    width: "1px",
                    targets: 0
                }],
                columns: [{
                        data: 'DT_Row_Index',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'fullname'
                    }, {
                        data: 'ebib'
                    }, {
                        data: 'entity'
                    }, {
                        data: 'distance_temp'
                    }, {
                        data: 'duration'
                    }, {
                        data: 'activities'
                    }, {
                        data: 'progress'
                    }, {
                        data: 'status'
                    }

                ],
                dom: 'lBfrtip',
                buttons: [
                    'excel'
                ],
                createdRow: function(row, data, dataIndex) {
                    if (data.row_class) {
                        $(row).addClass(data
                            .row_class); // hanya baris dengan rank 1 mendapatkan class 'top-rank'
                    }
                }
            });

            $('table#tableFemale').DataTable({
                processing: true,
                responsive: true,
                ajax: "{{ route('infiniteVirRun/data/female') }}",
                columnDefs: [{
                    width: "1px",
                    targets: 0
                }],
                columns: [{
                        data: 'DT_Row_Index',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'fullname'
                    }, {
                        data: 'ebib'
                    }, {
                        data: 'entity'
                    }, {
                        data: 'distance_temp'
                    }, {
                        data: 'duration'
                    }, {
                        data: 'activities'
                    }, {
                        data: 'progress'
                    }, {
                        data: 'status'
                    }

                ],
                dom: 'lBfrtip',
                buttons: [
                    'excel'
                ],
                createdRow: function(row, data, dataIndex) {
                    if (data.row_class) {
                        $(row).addClass(data
                            .row_class); // hanya baris dengan rank 1 mendapatkan class 'top-rank'
                    }
                }
            });
        });
    </script>
@endpush
