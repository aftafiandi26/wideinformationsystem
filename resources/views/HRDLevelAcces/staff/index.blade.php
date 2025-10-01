@extends('layout')

@section('title')
    (hr) Data Employee
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
        'c3' => 'active',
    ])
@stop

@push('style')
    <style>
        table#tables {
            font-size: 12px;
            width: 100%;
        }
    </style>
@endpush

@section('body')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.2/css/uikit.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.uikit.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Data Employee</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <a style="margin-bottom: 13px; text-align: left; width: 120px" class="btn btn-sm btn-primary"
                data-original-title="Add Data Employee" data-toggle="tooltip" data-placement="top"
                href="{!! URL::route('addEmployee') !!}"><span class="fa fa-user-plus"> New Employeee</span></a>
            <a style="margin-bottom: 13px; text-align: left; width: 120px" class="btn btn-sm btn-danger"
                data-original-title="Go to Import Data" data-toggle="tooltip" data-placement="top"
                href="{!! URL::route('Upload/Employee') !!}"><span class="fa fa-upload"> Import Excel</span></a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-bordered table-hover table-condensed" width="100%" id="tables">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Join Date</th>
                        <th>End Date</th>
                        <th>NIK</th>
                        <th>First<br>Name</th>
                        <th>Last<br>Name</th>
                        <th>Gender</th>
                        <th>Department</th>
                        <th>Place<br>Birth</th>
                        <th>Birth Date</th>
                        <th>Position</th>
                        <th>Education</th>
                        <th>Education Institution</th>
                        <th>Employee<br>Status</th>
                        <th>Phone</th>
                        <th>Religion</th>
                        <th>Annual</th>
                        <th>Exdo</th>
                        <th>Status</th>
                        <th>Address</th>
                        <th>ID Card</th>
                        <th>BPJS Kesehatan</th>
                        <th>BPJS Ketenagakerjaan</th>
                        <th>NPWP</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="modal-content">
                <!--  -->
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
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.19/filtering/row-based/range_dates.js"></script>
@push('js')
    <script>
        $(document).ready(function() {
            function myFunction() {
                var x = document.getElementById("myDIV");
                if (x.style.display === "none") {
                    x.style.display = "block";
                } else {
                    x.style.display = "none";
                }
            }

            function deasdasdasd() {
                location.reload();
            }

            $('table#tables').DataTable({
                processing: true,
                responsive: true,
                ajax: "{{ route('getEmployee') }}",
                columns: [{
                        data: 'DT_Row_Index',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'join_date'
                    }, {
                        data: 'end_date'
                    }, {
                        data: 'nik'
                    }, {
                        data: 'first_name'
                    }, {
                        data: 'last_name'
                    }, {
                        data: 'gender'
                    }, {
                        data: 'dept_category_name'
                    }, {
                        data: 'pob'
                    }, {
                        data: 'dob'
                    }, {
                        data: 'position'
                    }, {
                        data: 'education'
                    }, {
                        data: 'education_institution'
                    }, {
                        data: 'emp_status'
                    }, {
                        data: 'phone'
                    }, {
                        data: 'religion'
                    }, {
                        data: 'annual_leave_balance'
                    }, {
                        data: 'day_off_balance'
                    }, {
                        data: 'active'
                    }, {
                        data: 'address'
                    }, {
                        data: 'ktp'
                    }, {
                        data: 'bpjs_kesehatan'
                    }, {
                        data: 'bpjs_ketenagakerjaan'
                    }, {
                        data: 'npwped'
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

            $(document).on('click', '#tables tr td a[title="Detail"]', function(e) {
                var id = $(this).attr('data-role');
                $.ajax({
                    url: id,
                    success: function(e) {
                        $("#modal-content").html(e);
                    }
                });
            });
        });
    </script>
@endpush
