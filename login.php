<?php
    session_start();
    require 'db.php';

    if($_POST) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        if(login($email,$password)) {
            $_SESSION['password'] = $password;
            $_SESSION['email'] = $email;
            header("Location: index.php"); // redirect to index.php
            exit;
        } else {
            echo "error lol";
        }

        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    
    <link rel="stylesheet" href="login.css">
</head>
<body bgcolor="Black">
    <div class="login">
        <h1>Welcome!</h1>
        <form class="registration-form" method="POST">
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <p>Not yet a member? <a href="register.html">Sign up here</a></p>
    </div>
</body>
</html>