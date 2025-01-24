<!DOCTYPE html>
<html>

<head>
    <title>Status Update</title>
</head>

<body>
    <h1>Dear {{ $customer->name }}</h1>
    <p>Your current status is: <strong>{{ $customer->status }}</strong></p>
    <p>Thank you for being with us!</p>
</body>

</html>