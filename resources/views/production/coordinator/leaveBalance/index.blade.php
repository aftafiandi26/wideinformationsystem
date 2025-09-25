@extends('layout')

@section('title')
    Leave Balance
@stop

@section('top')
    @include('assets_css_1')
    @include('assets_css_2')
    @include('assets_css_3')
    @include('assets_css_4')
@stop

@section('navbar')
    @include('navbar_top')
    @include('navbar_left')
@stop

@push('style')
    <style>
        /* Warna untuk Annual Available */
        .annual-available-positive {
            color: #28a745 !important;
            /* Green */
            font-weight: bold;
        }

        .annual-available-zero {
            color: #ffc107 !important;
            /* Yellow */
            font-weight: bold;
        }

        .annual-available-negative {
            color: #dc3545 !important;
            /* Red */
            font-weight: bold;
        }

        /* Warna untuk Total Balance */
        .total-balance-positive {
            color: #28a745 !important;
            /* Green */
            font-weight: bold;
        }

        .total-balance-zero {
            color: #6c757d !important;
            /* Gray */
            font-weight: bold;
        }

        .total-balance-negative {
            color: #dc3545 !important;
            /* Red */
            font-weight: bold;
        }

        /* Warna untuk Exdo Balance */
        .exdo-balance-positive {
            color: #007bff !important;
            font-weight: bold;
        }

        .exdo-balance-zero {
            color: #6c757d !important;
            font-weight: bold;
        }

        .exdo-balance-negative {
            color: #dc3545 !important;
            font-weight: bold;
        }
    </style>
@endpush

@section('body')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Leave Balance</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-striped table-hover table-bordered table-condensed" width="100%" id="tables">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>NIK</th>
                        <th>Username</th>
                        <th>Employee</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th>Total Annual</th>
                        <th>Annual Taken</th>
                        <th>Annual Available</th>
                        <th>Annual Balance <sup>(until eoc)</sup></th>
                        <th>Total Exdo</th>
                        <th>Exdo Taken</th>
                        <th>Exdo Expired</th>
                        <th>Exdo Balance</th>
                        <th>Total Balance</th>
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
    @include('assets_script_4')
    @include('assets_script_7')
@stop

@push('js')
    <script>
        $(document).ready(function() {
            $('table#tables').DataTable({
                processing: true,
                responsive: true,
                ajax: "{{ route('coordinator/leave-balance/data') }}",
                columns: [{
                    data: 'DT_Row_Index',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'id'
                }, {
                    data: 'nik'
                }, {
                    data: 'username'
                }, {
                    data: 'fullname'
                }, {
                    data: 'position'
                }, {
                    data: 'emp_status'
                }, {
                    data: 'total_annual'
                }, {
                    data: 'annual_taken'
                }, {
                    data: 'annual_available',
                    render: function(data, type, row) {
                        var value = parseInt(data) || 0;
                        var className = '';

                        if (value > 0) {
                            className = 'annual-available-positive';
                        } else if (value === "0") {
                            className = 'annual-available-zero';
                        } else {
                            className = 'annual-available-negative';
                        }

                        return '<span class="' + className + '">' + value + '</span>';
                    }
                }, {
                    data: 'final_annual_balance'
                }, {
                    data: 'total_exdo'
                }, {
                    data: 'exdo_taken'
                }, {
                    data: 'exdo_expired'
                }, {
                    data: 'exdo_balance',
                    render: function(data, type, row) {
                        var value = parseInt(data) || 0;
                        var className = '';

                        if (value > 0) {
                            className = 'exdo-balance-positive';
                        } else if (value === "0") {
                            className = 'exdo-balance-zero';
                        } else {
                            className = 'exdo-balance-negative';
                        }

                        return '<span class="' + className + '">' + value + '</span>';
                    }
                }, {
                    data: 'total_balance',
                    render: function(data, type, row) {
                        var value = parseInt(data) || 0;
                        var className = '';

                        if (value > 0) {
                            className = 'total-balance-positive';
                        } else if (value === "0") {
                            className = 'total-balance-zero';
                        } else {
                            className = 'total-balance-negative';
                        }

                        return '<span class="' + className + '">' + value + '</span>';
                    }
                }],
                dom: 'lBfrtip',
                buttons: [
                    'excel'
                ],
            });
        });
    </script>
@endpush
