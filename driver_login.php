<?php
require "db.php";
session_start();

if($_POST) {
        $dremail = $_POST['dremail'];
        $drpassword = $_POST['drpassword'];
        if(logindriver($dremail,$drpassword)) {
            // $_SESSION['password'] = $password;
            $_SESSION['dremail'] = $dremail;
            header("Location: driver_dashboard.php"); // redirect to index.php
            exit();
        } else {
            echo "Error LOL";
        }

        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    
    <link rel="stylesheet" href="styles/login.css">
</head>
<body bgcolor="Black">
    <div class="login">
        <h1>Drivers login!</h1>
        <form class="registration-form" method="POST">
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="dremail" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="drpassword" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <p>Not yet a member? <a href="register.php">Sign up here</a></p>
        <p>Are you a regular customer? <a href="login.php">login here</a></p>
    </div>
</body>
</html>