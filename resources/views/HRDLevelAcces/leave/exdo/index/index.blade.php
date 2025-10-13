@extends('layout')

@section('title')
    (hr) Management Exdo Leave
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
        'c173' => 'active',
    ])
@stop

@section('body')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Exdo Leave
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-condensed table-hover table-stripped table-bordered" id="tables" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Employee</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Total</th>
                        <th>Taken</th>
                        <th>Expired</th>
                        <th>Remains</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Date Exdo Limit
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table tables-condensed table-hover table-stripped table-bordered" id="tablesLimit" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Employee</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Amount</th>
                        <th>Date Limit</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Form Exdo Leave on Progress
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table tables-condesed table-hover table-stripped table-bordered" id="tablesFormExdo"
                width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Employee</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Start Leave</th>
                        <th>End Leave</th>
                        <th>Day</th>
                        <th>Status Form</th>
                    </tr>
                </thead>
            </table>
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
            let urlTables = "{{ route('hrd/exdo-leave/data') }}";
            let urlTablesLimit = "{{ route('hrd/exdo-leave/data/limit') }}";
            let urlTablesFormExdo = "{{ route('hrd/exdo-leave/data/formProgress') }}";

            $('#tables').DataTable({
                processing: true,
                serverside: true,
                responsive: true,
                "dom": 'Blfrtip',
                "buttons": [{
                    extend: 'excel',
                    text: 'Excel',
                    titleAttr: 'transaction',
                    title: 'transaction'
                }],
                ajax: urlTables,
                columns: [{
                        "data": "DT_Row_Index",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "nik"
                    },
                    {
                        data: "fullname"
                    },
                    {
                        data: "position"
                    },
                    {
                        data: "department"
                    },
                    {
                        data: "total"
                    },
                    {
                        data: "taken"
                    },
                    {
                        data: "expired"
                    },
                    {
                        data: "remains"
                    }
                ]
            });

            $('#tablesLimit').DataTable({
                // processing: true,
                // serverside: true,
                responsive: true,
                "dom": "Blfrtip",
                "buttons": [{
                    extend: "excel",
                    text: "Excel",
                    titleAttr: "Date Exdo Limit",
                    title: "Date Exdo Limit"
                }],
                ajax: urlTablesLimit,
                columns: [{
                    data: "DT_Row_Index",
                    orderable: false,
                    searchable: false
                }, {
                    data: "nik"
                }, {
                    data: "fullname"
                }, {
                    data: "position"
                }, {
                    data: "department"
                }, {
                    data: "initial"
                }, {
                    data: "expired"
                }, {
                    data: "expired"
                }]
            });

            $('#tablesFormExdo').DataTable({
                // processing: true,
                // serverside: true,
                responsive: true,
                "dom": "Blfrtip",
                "buttons": [{
                    extend: "excel",
                    text: "Excel",
                    titleAttr: "Date Exdo Limit",
                    title: "Date Exdo Limit"
                }],
                ajax: urlTablesFormExdo,
                columns: [{
                    data: "DT_Row_Index",
                    orderable: false,
                    searchable: false
                }, {
                    data: "request_nik"
                }, {
                    data: "request_by"
                }, {
                    data: "request_position"
                }, {
                    data: "request_dept_category_name"
                }, {
                    data: "leave_date"
                }, {
                    data: "end_leave_date"
                }, {
                    data: "total_day"
                }, {
                    data: "statusForm"
                }]
            });
        });
    </script>
@endpush
