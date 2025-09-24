@extends('layout')

@section('title')
    Leave Applying (New Logic)
@stop

@section('top')
    @include('assets_css_1')
    @include('assets_css_2')
    <style>
        /* Page Header */
        .page-header {
            border-bottom: 3px solid #337ab7;
            margin-bottom: 30px;
            padding-bottom: 15px;
        }

        .page-header h1 {
            color: #2c3e50;
            font-weight: 300;
        }

        .page-header small {
            font-size: 14px;
            color: #7f8c8d;
        }

        /* Card Hover Effects */
        .panel {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .panel:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* Panel Headers */
        .panel-primary .panel-heading {
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
            color: white;
        }

        .panel-info .panel-heading {
            background: linear-gradient(135deg, #5bc0de, #46b8da);
            border: none;
            color: white;
        }

        .panel-warning .panel-heading {
            background: linear-gradient(135deg, #f0ad4e, #ec971f);
            border: none;
            color: white;
        }

        /* Huge Numbers */
        .huge {
            font-size: 40px;
            font-weight: bold;
            line-height: 1;
        }

        /* Badge Styles */
        .badge {
            font-size: 12px;
            padding: 6px 10px;
            border-radius: 15px;
        }

        .badge-info {
            background-color: #5bc0de;
        }

        .badge-warning {
            background-color: #f0ad4e;
        }

        .badge-success {
            background-color: #5cb85c;
        }

        .badge-primary {
            background-color: #337ab7;
        }

        .badge-secondary {
            background-color: #6c757d;
        }

        /* Table Styles */
        .table th {
            background-color: #f8f9fa;
            border: none;
            font-weight: 600;
            color: #2c3e50;
        }

        .table td {
            border: none;
            border-bottom: 1px solid #ecf0f1;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .huge {
                font-size: 28px;
            }

            .panel-heading .row {
                text-align: center;
            }

            .panel-heading .col-xs-3 {
                display: none;
            }

            .panel-footer {
                padding: 10px 15px;
            }

            .panel-footer span {
                font-size: 13px;
            }

            .btn-sm {
                padding: 6px 12px;
                font-size: 12px;
            }

            .panel-body {
                padding: 15px;
            }

            /* Better spacing for mobile cards */
            .col-xs-12 {
                margin-bottom: 20px;
            }
        }

        @media (max-width: 480px) {
            .huge {
                font-size: 24px;
            }

            .panel-heading {
                padding: 10px 15px;
            }

            .panel-footer {
                padding: 8px 15px;
            }

            .panel-footer .pull-left,
            .panel-footer .pull-right {
                float: none !important;
                display: block;
                text-align: center;
                margin: 5px 0;
            }

            .btn-sm {
                padding: 8px 16px;
                font-size: 12px;
                width: 100%;
                margin-top: 8px;
            }

            .panel-body {
                padding: 12px;
            }

            /* Better card spacing */
            .col-xs-12 {
                margin-bottom: 15px;
            }
        }

        /* Mobile-first approach */
        @media (max-width: 992px) {
            .col-lg-3 {
                margin-bottom: 15px;
            }
        }

        /* Extra small devices */
        @media (max-width: 320px) {
            .huge {
                font-size: 18px;
            }

            .panel-heading {
                padding: 6px 8px;
            }

            .panel-body {
                padding: 6px;
            }

            .panel-footer {
                padding: 4px 8px;
            }

            .btn-sm {
                padding: 4px 8px;
                font-size: 10px;
            }
        }

        /* Table responsive */
        @media (max-width: 768px) {
            .table-responsive {
                border: none;
            }

            .table th,
            .table td {
                padding: 8px 4px;
                font-size: 12px;
            }

            .badge {
                font-size: 10px;
                padding: 4px 6px;
            }
        }

        @media (max-width: 480px) {

            .table th,
            .table td {
                padding: 6px 2px;
                font-size: 11px;
            }

            .table th {
                font-size: 10px;
            }

            .badge {
                font-size: 9px;
                padding: 3px 5px;
            }
        }
    </style>
@stop

@section('navbar')
    @include('navbar_top')
    @include('navbar_left', [
        'c2' => 'active',
    ])
@stop

@section('body')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <i class="fa fa-calendar-check-o"></i> Leave Application Dashboard
                <small class="text-muted">Modern Leave Management</small>
            </h1>
        </div>
    </div>

    <!-- Leave Balance Cards -->
    <div class="row">
        <!-- Annual Leave Available Card -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="margin-bottom: 20px;">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3 hidden-xs">
                            <i class="fa fa-calendar fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $leaveBalance['annual_available'] }}</div>
                            <div>Days Available</div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <span class="pull-left">Annual Available</span>
                    <span class="pull-right">
                        @if ($leaveBalance['annual_available'] > 0)
                            <a href="#" class="btn btn-success btn-sm">
                                <i class="fa fa-plus"></i> Apply
                            </a>
                        @else
                            <span class="text-muted">No leave available</span>
                        @endif
                    </span>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        <!-- Total Annual Leave Card -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="margin-bottom: 20px;">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3 hidden-xs">
                            <i class="fa fa-calendar-plus-o fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $leaveBalance['annual_balance'] }}</div>
                            <div>Annual Balance</div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <span class="pull-left">Annual Balance</span>
                    <span class="pull-right">
                        @if ($leaveBalance['annual_available'] > 0)
                            <a href="#" class="btn btn-success btn-sm">
                                <i class="fa fa-plus"></i> Apply
                            </a>
                        @else
                            <span class="text-muted">No leave available</span>
                        @endif
                    </span>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        <!-- Annual Balance Card -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="margin-bottom: 20px;">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3 hidden-xs">
                            <i class="fa fa-calendar-times-o fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">0</div>
                            <div>Other Leave</div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <span class="pull-left">Other Leave</span>
                    <span class="pull-right">
                        <span class="text-muted">Not available</span>
                    </span>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        <!-- Exdo Balance Card -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="margin-bottom: 20px;">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3 hidden-xs">
                            <i class="fa fa-gift fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $exdoBalance['remaining_exdo'] }}</div>
                            <div>Exdo Available</div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <span class="pull-left">Exdo Leave</span>
                    <span class="pull-right">
                        @if ($exdoBalance['remaining_exdo'] > 0)
                            <a href="#" class="btn btn-danger btn-sm">
                                <i class="fa fa-plus"></i> Use
                            </a>
                        @else
                            <span class="text-muted">No Exdo available</span>
                        @endif
                    </span>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Leave Information -->
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-info-circle"></i> Leave Balance Details
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="info">
                                    <th><i class="fa fa-list"></i> Leave Type</th>
                                    <th class="text-center"><i class="fa fa-calendar"></i> Total</th>
                                    <th class="text-center"><i class="fa fa-check"></i> Taken</th>
                                    <th class="text-center"><i class="fa fa-clock-o"></i> Expired</th>
                                    <th class="text-center"><i class="fa fa-clock-o"></i> Available</th>
                                    <th class="text-center"><i class="fa fa-balance-scale"></i> Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>Annual Leave</strong>
                                        <br><small class="text-muted">Regular annual leave entitlement</small>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge badge-info">{{ number_format($leaveBalance['total_annual'], 1) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge badge-warning">{{ number_format($leaveBalance['annual_taken'], 1) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-secondary">0.0</span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge badge-success">{{ number_format($leaveBalance['annual_available'], 1) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge badge-primary">{{ number_format($leaveBalance['annual_balance'], 1) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Exdo Leave</strong>
                                        <br><small class="text-muted">Extra day off</small>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge badge-info">{{ number_format($exdoBalance['total_exdo'], 1) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge badge-warning">{{ number_format($exdoBalance['taken_exdo'], 1) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge badge-danger">{{ number_format($exdoBalance['expired_exdo'], 1) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge badge-success">{{ number_format($exdoBalance['remaining_exdo'], 1) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge badge-primary">{{ number_format($exdoBalance['valid_exdo'], 1) }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-info-circle"></i> Exdo Leave Records
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-condensed table-striped" id="exdoList" width="100%">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('bottom')
    @include('assets_script_1')
    @include('assets_script_2')
@stop

@push('js')
    <script>
        $(document).ready(function() {
            $('#exdoList').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('leave.exdo.list') }}',
                columns: [{
                        data: 'expired',
                        title: 'Expired Date',
                        className: 'text-center'
                    },
                    {
                        data: 'initial',
                        title: 'Days',
                        className: 'text-center'
                    },
                    {
                        data: 'limit',
                        title: 'Status',
                        className: 'text-center'
                    },
                    {
                        data: 'note',
                        title: 'Note',
                        className: 'text-center'
                    },
                ],
                order: [
                    [0, 'desc']
                ], // Sort by expired date
                pageLength: 10,
                responsive: true,
                language: {
                    processing: "Loading...",
                    emptyTable: "No Exdo records found"
                }
            });
        });
    </script>
@endpush
