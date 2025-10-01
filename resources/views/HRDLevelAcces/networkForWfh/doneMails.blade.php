<!DOCTYPE html>
<html>

<head>
    <title>Email</title>
</head>

<body>
    <p>Hi there,</p>
    <p>"ACCESS NETWORK CHECK FOR WFH" has been completed by :</p>
    <ul>
        @foreach ($data as $key => $value)
            <li><strong>{{ $key }}:</strong> {{ $value }}</li>
        @endforeach
    </ul>
    <br>
    <p>Regard's</p>
    <p>-WIS-</p>
</body>

</html>
