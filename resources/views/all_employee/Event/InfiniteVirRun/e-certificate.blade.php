<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        @font-face {
            font-family: 'goeverywhere';
            src: url('{{ asset('assets/fonts/Go Everywhere.otf') }}') format('opentype');
        }

        @page {
            size: 43.4cm 30.6cm;
            margin: 0;
        }

        body {
            margin: 0;
            width: 43.4cm;
            height: 30.6cm;
            background: url("{{ asset('assets/e-cert/event/infinitevirtualrun2025.png') }}") no-repeat center center;
            background-size: cover;
        }

        p.text-bold {
            font-weight: bold;
            font-size: 65px;
            text-align: center;
        }

        div#name_participant {
            margin-left: 17cm;
            margin-top: 9.8cm;
            width: 21cm;
        }

        div#tables {
            margin-left: 17cm;
            margin-top: -1.3cm;
            width: 21cm;
        }

        table {
            font-size: 34px;
            font-weight: bold;
            font-family: 'goeverywhere', sans-serif;
            margin: 0 auto;
            border-collapse: collapse;
            width: auto;
        }


        .div-line {
            margin-left: 17cm;
            margin-top: -1.5cm;
            width: 21cm;
            /* panjang garis */
            height: 2px;
            /* ketebalan garis */
            background-color: black;
            /* warna garis */

        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row" id="name_participant">
            <p class="text-bold">{{ $user->getFullName() }}</p>
        </div>
        {{-- <div class="div-line"></div> --}}
        <div class="row" id="tables">
            <table class="">
                <tbody>
                    <tr>
                        <td style="text-align: right;">E-BIB</td>
                        <td>:</td>
                        <td>24007</td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Total KM</td>
                        <td>:</td>
                        <td>45.67 KM</td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Total Time</td>
                        <td>:</td>
                        <td>5h 14m 53s</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
