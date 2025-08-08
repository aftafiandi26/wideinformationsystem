<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        @page {
            size: 1080px 1350px;
            /* Bisa diganti dengan size: 15cm 18.75cm; 1080px 1350px */
            margin: 0;
        }

        @font-face {
            font-family: 'Limelight';
            src: url('{{ asset('assets/fonts/Limelight-Regular.ttf') }}') format('truetype');
        }

        body {
            margin: 0;
            padding: 0;
            width: 1080px;
            height: 1350px;
            background: url("{{ asset('assets/e-cert/event/INFINITEVIRTUALRUN2025_ECERT_TEMPLATE.png') }}") no-repeat center center;
            background-size: cover;
            box-sizing: border-box;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            /* 860px */
        }

        #name_participant {
            margin-left: 110px;
            color: white;
            font-weight: bold;
            font-size: 54px;
            width: 860px;
            text-align: center;
            white-space: normal;
            overflow: hidden;
            text-overflow: ellipsis;
            /* font-family: "Limelight", sans-serif; */

        }

        #ebib {
            margin-top: -45px;
            margin-left: 110px;
            font-weight: bold;
            font-size: 50px;
            color: white;
            width: 860px;
            text-align: center;
        }

        #distance {
            color: white;
            font-size: 48px;
            font-weight: bold;
            margin-left: 110px;
            width: 860px;
            text-align: center;
            margin-top: 55px;
        }

        #times {
            color: white;
            font-size: 48px;
            font-weight: bold;
            margin-left: 110px;
            width: 860px;
            text-align: center;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="row">
        <p id="name_participant" style="margin-top: {{ $marginTop }}px;">{{ $user->getFullName() }}</p>
    </div>
    <div class="row">
        <p id="ebib">{{ $user->eventRegister()->ebib }}</p>
    </div>
    <div class="row">
        <p id="distance">{{ $distance }} km</p>
    </div>
    <div class="row">
        <p id="times">{{ $duration }}</p>
    </div>
</body>


</html>
