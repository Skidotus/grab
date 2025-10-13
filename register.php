<?php
    require 'db.php';

    if($_POST) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        register($username, $email, $password);
    }


?>


<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-eval'; style-src 'self' 'unsafe-inline';">
    <title>Registration Page</title>

    <link rel="stylesheet" href="/styles/register.css">
</head>
<body bgcolor="Black">
    <div class="login">
        <h1>Registration</h1>
        <form class="registration-form" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Register</a></button>
        </form>
        <p>Already a member? <a href="login.php">Login here</a></p>
    </div>

</body>
</html>

