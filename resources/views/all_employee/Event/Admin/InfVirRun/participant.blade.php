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
    @include('asset_feedbackErrors')
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">List Participants</div>
                <div class="panel-body">
                    <table class="table table-bordered table-hover table-striped table-condensed" width="100%"
                        id="tables">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>EBIB</th>
                                <th>Period</th>
                                <th>Status</th>
                                <th>Strava Profile</th>
                                <th>E-Certificate</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div id="modalRemove" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" id="popUp">

        </div>
    </div>

    <div id="submission" class="modal fade" tabindex="-1" role="dialog">
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
            var element = document.getElementById('participant');
            element.classList.add('press');

            $('table#tables').DataTable({
                processing: true,
                responsive: true,
                ajax: "{{ route('admin/infinite-virtual-run/participant/data') }}",
                columns: [{
                        data: 'DT_Row_Index',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'getFullname'
                    }, {
                        data: 'ebib'
                    }, {
                        data: 'periode'
                    }, {
                        data: 'active'
                    }, {
                        data: 'stravaURL'
                    }, {
                        data: 'ecert'
                    }, {
                        data: 'actions',
                        orderable: false,
                        searchable: false
                    }

                ],
                dom: 'lBfrtip',
                buttons: [
                    'excel'
                ],
            });

            $(document).on('click', 'a#idRemove', function(e) {
                var id = $(this).attr('data-role');

                $.ajax({
                    url: id,
                    success: function(e) {
                        $("div#popUp").html(e);
                        $("div#popUp").show();
                    },
                    error: function() {
                        $("#popUp .modal-content").html(
                            '<div class="modal-body"><p>Terjadi kesalahan saat memuat data.</p></div>'
                        );
                        $("#popUp").modal('show');
                    }
                });
            });
        });
    </script>
@endpush
