@extends('layout')

@section('title')
    Leave
@stop

@section('top')
    @include('assets_css_1')
    @include('assets_css_2')
@stop

@section('navbar')
    @include('navbar_top')
    @include('navbar_left', [
        'c2' => 'active',
    ])
@stop
@section('body')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">Leave Balance</h2>
            </div>
        </div>
        <!-- Leave Application Section -->
        <div class="row">
            <div class="col-lg-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Apply Leave</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Leave Type</th>
                                        <th>Available</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Annual Leave</td>
                                        <td>
                                            <span class="badge badge-primary">{{ isset($totalAnnual) ? $totalAnnual : 0 }}
                                                days</span>
                                        </td>
                                        <td>
                                            @if ((isset($totalAnnual) ? $totalAnnual : 0) > 0)
                                                <a href="{{ route('outsource/leave/outsource/create') }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fa fa-plus"></i> Apply
                                                </a>
                                            @else
                                                <button class="btn btn-default btn-sm" disabled>
                                                    <i class="fa fa-ban"></i> No Balance
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Etc Leave</td>
                                        <td>
                                            <span class="badge badge-info">Available</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('outsource/leave/outsource/etc') }}"
                                                class="btn btn-info btn-sm">
                                                <i class="fa fa-plus"></i> Apply
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Leave Information</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group">
                            <div class="list-group-item">
                                <h4 class="list-group-item-heading">Employment Status</h4>
                                <p class="list-group-item-text">Outsource Employee</p>
                            </div>
                            <div class="list-group-item">
                                <h4 class="list-group-item-heading">Join Date</h4>
                                <p class="list-group-item-text">{{ isset($user->join_date) ? $user->join_date : 'N/A' }}</p>
                            </div>
                            <div class="list-group-item">
                                <h4 class="list-group-item-heading">Leave Calculation</h4>
                                <p class="list-group-item-text">1 month = 1 day annual leave</p>
                            </div>
                            <div class="list-group-item">
                                <h4 class="list-group-item-heading">Current Year</h4>
                                <p class="list-group-item-text">{{ isset($currentYear) ? $currentYear : date('Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Progression Chart -->
        @if (isset($monthlyProgression) && count($monthlyProgression) > 0)
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Monthly Leave Progression</h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="monthlyProgressionTable">
                                    <thead>
                                        <tr>
                                            <th>Month</th>
                                            <th>Months Worked</th>
                                            <th>Earned Leave</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($monthlyProgression as $progression)
                                            <tr>
                                                <td>{{ $progression['month'] }}</td>
                                                <td>{{ $progression['months_worked'] }}</td>
                                                <td>{{ $progression['earned_leave'] }} days</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="dataTables_info" id="monthlyProgressionTable_info" role="status"
                                        aria-live="polite">
                                        Showing entries
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="dataTables_paginate paging_simple_numbers"
                                        id="monthlyProgressionTable_paginate">
                                        <ul class="pagination">
                                            <!-- Pagination will be generated by DataTables -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@stop

@section('bottom')
    @include('assets_script_1')
    @include('assets_script_2')
@stop
@push('js')
    <script>
        $(document).ready(function() {
            // Monthly Progression Table with Pagination
            $('#monthlyProgressionTable').DataTable({
                "pageLength": 6, // Show 12 months per page (1 year)
                "lengthMenu": [
                    [6, 12, 24, 48, -1],
                    [6, 12, 24, 48, "All"]
                ],
                "order": [
                    [0, "desc"]
                ], // Sort by month descending (newest first)
                "language": {
                    "lengthMenu": "Show _MENU_ months per page",
                    "zeroRecords": "No monthly progression data found",
                    "info": "Showing _START_ to _END_ of _TOTAL_ months",
                    "infoEmpty": "Showing 0 to 0 of 0 months",
                    "infoFiltered": "(filtered from _MAX_ total months)",
                    "search": "Search months:",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                },
                "dom": '<"row"<"col-sm-6"l><"col-sm-6"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
                "responsive": true,
                "autoWidth": false,
                "processing": true,
                "deferRender": true
            });
        });
    </script>
@endpush
