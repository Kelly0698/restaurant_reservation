<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Future Reserve It</title>
</head>
<body>
    <h2>Welcome to Future Reserve It, {{ $user->name }}</h2>
    <p>Your account has been created successfully.</p>
    <p>Here are your login details:</p>
    <p>Email: {{ $user->email }}</p>
    <p>Password: {{ $password }}</p>
    <p>Please keep your password confidential and change it after logging in for security reasons.</p>
    <p>Thank you!</p>
</body>
</html>
