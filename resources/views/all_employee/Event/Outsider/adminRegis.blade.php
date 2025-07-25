@extends('layout')

@section('title')
    Registration - Infinite Virtual Run
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
    <style></style>
@endpush
@section('body')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">External Registration</h1>
        </div>
    </div>

    @include('asset_feedbackErrors')

    <div class="row">
        <div class="col-lg-12">
            <button class="btn btn-primary btn-sm pull-right" id="create" data-toggle="modal" data-target="#modalCreate"
                data-role="{{ route('outsider/infiniteVirRun/run/adminRegistration/create') }}"><i class="fa fa-plus"></i>
                Create</button>
            <a href="{{ route('admin/infinite-virtual-run/announcement/external') }}"
                class="btn btn-sm btn-default pull-right" style="margin-right: 3px;"><i class="fa fa-bullhorn"></i>
                Announcement</a>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered table-condensed table-striped table-hover" id="tables" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>EBIB</th>
                        <th>Participant</th>
                        <th>Email</th>
                        <th>Strava Profile</th>
                        <th>Company</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div id="modalCreate" class="modal fade" role="dialog">
        <div class="modal-dialog" id="modalRegister">

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
            $("button#create").on('click', function() {
                let url = $(this).attr('data-role');

                $.ajax({
                    url: url,
                    success: function(e) {
                        $("#modalRegister").html(e);
                    }
                });
            });

            $('table#tables').DataTable({
                processing: true,
                responsive: true,
                ajax: "{{ route('outsider/infiniteVirRun/run/adminRegistration/data') }}",
                columnDefs: [{
                    width: "1px",
                    targets: 0
                }],
                columns: [{
                        data: 'DT_Row_Index',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'ebib'
                    }, {
                        data: 'fullname'
                    }, {
                        data: 'email'
                    }, {
                        data: 'strava'
                    }, {
                        data: 'company'
                    }, {
                        data: 'actions',
                        orderable: false,
                        searchable: false,
                    }

                ],
                dom: 'lBfrtip',
                buttons: [
                    'excel'
                ],
            });

            $(document).on('click', 'a#edit', function(e) {
                var id = $(this).attr('data-role');

                $.ajax({
                    url: id,
                    success: function(e) {
                        $("div#modalRegister").html(e);
                        $("div#modalRegister").show();
                    },
                    error: function() {
                        $("#modalRegister .modal-content").html(
                            '<div class="modal-body"><p>Terjadi kesalahan saat memuat data.</p></div>'
                        );
                        $("#modalRegister").modal('show');
                    }
                });
            });

            $(document).on('click', 'a#delete', function(e) {
                var id = $(this).attr('data-role');

                $.ajax({
                    url: id,
                    success: function(e) {
                        $("div#modalRegister").html(e);
                        $("div#modalRegister").show();
                    },
                    error: function() {
                        $("#modalRegister .modal-content").html(
                            '<div class="modal-body"><p>Terjadi kesalahan saat memuat data.</p></div>'
                        );
                        $("#modalRegister").modal('show');
                    }
                });
            });
        });
    </script>
@endpush
