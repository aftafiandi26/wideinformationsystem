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
        button#submit {
            border-radius: 15px;
            background-color: lightgreen;
            color: black;
        }

        button#reset {
            border-radius: 15px;
            margin-right: 1%;
        }

        button#submit:hover {
            background-color: darkgreen;
            color: whitesmoke;
        }

        .text-bold {
            font-weight: bold;
            font-size: 16px;
        }

        span.text-danger {
            color: red;
            font-weight: bold;
            font-size: 11px;
        }

        .mvTime {
            color: #a94442;
        }

        @media (max-width: 780px) {
            .col-lg-4 {
                margin-bottom: 10px;
                padding-left: 5px;
                padding-right: 5px;
                width: 100%;
                float: none;
            }

            .col-lg-4 .input-group {
                width: 100%;
                display: block;
            }

            .col-lg-4 .input-group .form-control {
                width: 100%;
                box-sizing: border-box;
            }
        }
    </style>
@endpush
@section('body')
    @include('all_employee.Event.InfiniteVirRun.menuVirRun')
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <p class="text-bold">Submission</p>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" action="{{ route('infiniteVirRun/submission/post') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group @if ($errors->has('strava')) has-error has-feedback @endif">
                            <label class="control-label col-sm-2" for="strava">Strava Active URL:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="strava"
                                    placeholder="https://www.strava.com/activities/12031600253">
                                @if ($errors->has('strava'))
                                    <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                                    <span class="text-danger">{{ $errors->first('strava') }}</span>
                                @endif
                            </div>

                        </div>
                        <div class="form-group">
                            <label
                                class="control-label col-sm-2 @if ($errors->has('hours')) mvTime @elseif($errors->has('minute')) mvTime @elseif($errors->has('second')) mvTime @endif "
                                for="mvtime">Moving Time:</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-lg-4 @if ($errors->has('hours')) has-error has-feedback @endif">
                                        <div class="input-group">
                                            <input id="mvtime" type="number" class="form-control" name="hours"
                                                step="0" max="12" min="0" value="0">
                                            <span class="input-group-addon">Hours</span>
                                        </div>
                                        @if ($errors->has('hours'))
                                            <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('hours') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-lg-4 @if ($errors->has('minute')) has-error has-feedback @endif">
                                        <div class="input-group">
                                            <input id="mvtime" type="number" class="form-control" name="minute"
                                                step="0" placeholder="60" max="59" min="0"
                                                value="0">
                                            <span class="input-group-addon">Minutes</span>
                                        </div>
                                        @if ($errors->has('minute'))
                                            <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('minute') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-lg-4 @if ($errors->has('second')) has-error has-feedback @endif">
                                        <div class="input-group">
                                            <input id="mvtime" type="number" class="form-control" name="second"
                                                step="0" placeholder="60" max="59" min="0"
                                                value="0">
                                            <span class="input-group-addon">Seconds</span>
                                        </div>
                                        @if ($errors->has('second'))
                                            <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                                            <span class="text-danger">{{ $errors->first('second') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group @if ($errors->has('distanceInput')) has-error has-feedback @endif">
                            <label class="control-label col-sm-2" for="distanceInput">Distance:</label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input id="distanceInput" type="text" class="form-control" name="distanceInput"
                                        step="0.01" placeholder="24.50">
                                    <span class="input-group-addon">Km</span>
                                </div>
                                @if ($errors->has('distanceInput'))
                                    <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                                    <span class="text-danger">{{ $errors->first('distanceInput') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-default pull-right" id="submit">Submit</button>
                                <button type="reset" class="btn btn-default pull-right" id="reset">Reset</button>
                            </div>
                        </div>
                    </form>

                </div>

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
@push('js')
    <script src="{{ asset('assets/js/datetimepicker/jquery.datetimepicker.full.js') }}"></script>
    <script>
        $(document).ready(function() {
            var element = document.getElementById('submission');
            element.classList.add('press');

            $("button#buttonRegSubmit").on('click', function(e) {
                e.preventDefault();
                let checkbox = document.getElementById('joinEvent');
                let checkValue = null;
                let form = document.getElementById('formREG');

                if (checkbox.checked) {
                    checkValue = true;
                    form.submit();
                } else {
                    checkValue = false;
                    alert("Please check the checkbox first");
                }
            });
        });
    </script>
@endpush
