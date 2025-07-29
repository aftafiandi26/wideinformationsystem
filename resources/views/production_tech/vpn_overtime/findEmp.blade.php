@extends('layout')

@section('title')
    Production Technology | VPN
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
    <style>
        button.form {
            border-radius: 7px;
        }

        button.form:hover {
            background-color: whitesmoke;
            color: black;
        }
    </style>
@endpush
@section('body')

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Employes VPN Overtime</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('prodTech/manage/vpn/list/findTable') }}" method="post" id="form" class="form-inline">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="start">Date:</label>
                    <input type="date" class="form-control" id="start" name="start" required
                        value="{{ $started }}">
                    -
                    <input type="date" class="form-control" id="start" name="end" required
                        value="{{ $ended }}">
                    <button type="submit" class="btn btn-info form"><i class="fa fa-search"></i> find</button>
                </div>
            </form>
        </div>
    </div>

    <hr>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered table-condensed table-striped table-hover" id="empTable" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Employe</th>
                        <th>Position</th>
                        <th>Workstation</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Duration <sup>(h:m:s)</sup></th>
                    </tr>
                </thead>
            </table>
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
            $('table#empTable').DataTable({
                processing: true,
                responsive: true,
                ajax: "{{ route('prodTech/manage/vpn/list/findDataTables', [$started, $ended]) }}",
                columns: [{
                        data: 'DT_Row_Index',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'fullname'
                    }, {
                        data: 'position'
                    }, {
                        data: 'workstation'
                    }, {
                        data: 'startovertime'
                    }, {
                        data: 'endovertime'
                    }, {
                        data: 'duration'
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
