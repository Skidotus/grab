<?php
    require 'db.php';

    if($_POST) {
        $drusername = $_POST['drusername'];
        $dremail = $_POST['dremail'];
        $drpassword = $_POST['drpassword'];
        $drphonenumber = $_POST['drphonenumber'];
        $draddress = $_POST['draddress'];
        $drbirthdate = $_POST['drbirthdate'];
        if(registerdriver($drusername, $dremail, $drpassword, $drphonenumber, $draddress, $drbirthdate)) {
            header("Location: driver_login.php");
            exit();
        } else {
            
        }
    }


?>


<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=deviedit_userce-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-eval'; style-src 'self' 'unsafe-inline';">
    <title>Registration Page</title>

    <link rel="stylesheet" href="styles/register.css">
</head>
<body bgcolor="Black">
    <div class="login">
        <h1>Driver Registration</h1>
        <form class="registration-form" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="drusername" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="dremail" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="drpassword" required>
            </div>
                        <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="text" id="phonenumber" name="drphonenumber" required>
            </div>
            <div class="form-group">
                <label for="birthdate">Birth Date:</label>
                <input type="date" id="birthdate" name="drbirthdate" required>
            </div>
            <div class="form-group">
                <label for="address">Address (optional):</label>
                <input type="text" id="address" name="draddress">
            </div>
            <button type="submit" class="btn">Register</a></button>
        </form>
        <p>Already a driver? <a href="driver_login.php">Login here</a></p>
    </div>

</body>
</html>

