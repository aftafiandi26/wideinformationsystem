

@extends('layout')

@section('title')
Self Declaration
@stop

@section('top')
@include('assets_css_1')
@include('assets_css_2')
@stop

@push('style')
<style>
    .text-grey {
        color: grey;
        font-style: italic;
    }
    .text-grey:hover {
        color: black;
    }
    .text-underline {
        text-decoration: underline;        
    }
    .bold-text {
        font-weight: bold;
    }
    .margin-top {
        margin-top: 20px;
    }
    .marka {
        color:  rgb(184, 177, 177);
    }   
    ol li p {
        text-align: justify;
    } 
    .panel:hover {
        box-shadow: 2px 2px 4px 2px rgba(0, 0, 0, 0.2);
    }    

    @media only screen and (max-width: 480px) {
        .wise {
            margin-left: 0px;
        }       
    }
    @media only screen and (min-width: 480px) {
        .wise {
            margin-left: 30px;
        }
        table#tableForm tbody tr th {
            width: 15%;
        }
        .scrolling-div {
            overflow-y: scroll;
            height: 600px;
        }
    }
</style>
@endpush

@section('navbar')
@include('navbar_top')
@include('navbar_left', [
'c2' => 'active',
'c16' => 'active'
])
@stop
@section('body')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">e-Form Self Declaration</h1>
    </div>    
</div>

<div class="row">
    <div class="col-lg-8" id="leave">
        <div class="panel panel-info">
            <div class="panel-header">
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="text-center bold-text">Leave Appliaction Form <span>(Annual)</span></h4>
                        <hr>
                    </div>
                </div>
            </div>
            <div class="panel-body">                
                <div class="row">
                    <div class="col-lg-12 table-responsive">
                        <table class="table table-condensed table-borderless" id="tableForm">                           
                            <tbody>
                                <tr>
                                    <td>Request By</td>
                                    <td>: <span class="bold-text">{{ $data['request_by'] }}</span></td>
                                    <th></th>
                                    <td>NIK</td>
                                    <td>: <span class="bold-text">{{ $data['request_nik'] }}</span></td>
                                </tr>
                                <tr>
                                    <td>Period</td>
                                    <td>: <span class="bold-text">{{ $data['period'] }}</span></td>
                                    <th></th>
                                    <td>Position</td>
                                    <td>: <span class="bold-text">{{ $data['request_position'] }}</span></td>
                                </tr>
                                <tr>
                                    <td>Join Date</td>
                                    <td>: <span class="bold-text">{{ date('F, m Y' , strtotime($data['request_join_date'])) }}</span></td>
                                    <th></th>
                                    <td>Department</td>
                                    <td>: <span class="bold-text">{{ $data['request_dept_category_name'] }}</span></td>
                                </tr>
                                <tr>
                                    <td>Contact Address</td>
                                    <td colspan="3">: <span class="bold-text">{{ auth()->user()->address. ', ' .auth()->user()->area .', ' .auth()->user()->city}}</span></td>
                                </tr>
                                <tr>
                                    <td>Leave Category</td>
                                    <td>: <span class="bold-text">{{ $leaveCategory }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-lg-12">
                        <h5 class="bold-text text-center">Personal Verification</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 table-responsive">
                        <table class="table-condensed table table-borderless">
                            <thead>
                                <tr>
                                    <th>Period</th>
                                    <th>Entitlement</th>
                                    <th>Taken</th>
                                    <th>Pending</th>
                                    <th>Requested</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $data['period'] }}</td>
                                    <td>{{ $data['entitlement'] }}</td>
                                    <td>{{ $data['taken'] }}</td>
                                    <td>{{ $data['pending'] }}</td>
                                    <td>{{ $data['total_day'] }}</td>
                                    <td>{{ $data['remain'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 table-responsive">
                        <table class="table table-condensed table-borderless" id="tableForm">
                            <tbody>
                                <tr>
                                    <td>Approved Leave Form</td>
                                    <td>: <span class="bold-text">{{ $data['leave_date'] }}</span></td>
                                    <th></th>
                                    <td>Until</td>
                                    <td>: <span class="bold-text">{{ $data['end_leave_date'] }}</span></td>
                                </tr>
                                <tr>
                                    <td>Back to Work On</td>
                                    <td>: <span class="bold-text">{{ $data['back_work'] }}</span></td>
                                </tr>
                                <tr>
                                    <td>Contact phone during leave</td>
                                    <td>: <span class="bold-text">{{ auth()->user()->phone }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row margin-top">
                    <div class="col-lg-12">
                        <h5 class="bold-text">Reason :</h5>                        
                        <p>{{ $data['reason_leave']}}</p>
                    </div>
                </div>               
            </div>
            <div class="panel-footer">
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <form action="{{ route('leave/forwarder/annual/post') }}" method="post"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-sm btn-success">Apply</button>
                                <a onclick="backPage()" class="btn btn-sm btn-danger">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
        </div>
    </div>   
        
</div>
@stop

@section('bottom')
  @include('assets_script_1')
@stop

@push('js')
<script>  
    function setCookie(nama, nilai, durasiMenit) {
        var d = new Date();
        d.setTime(d.getTime() + durasiMenit * 60 * 1000);
        var expires = "expires=" + d.toUTCString();
        document.cookie = nama + "=" + nilai + ";" + expires + ";path=/";
    }

    function backPage()
    {
        setCookie('data', '', 1);
        setCookie('url', '', 1);
        window.location.href = "{{ $url }}";
    }

    setTimeout(function() { 
        setCookie("data", "", 1);      
        setCookie("url", "", 1);      
        window.location.href = "{{ $url }}";
    }, 600000);
</script>
@endpush