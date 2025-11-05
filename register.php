<?php
    require 'db.php';

    if($_POST) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $phonenumber = $_POST['phonenumber'];
        $address = $_POST['address'];
        $birthdate = $_POST['birthdate'];

        $picture = $_FILES['uploadimage'];
        $filename = uploadImage($picture);
        

        if(register($username, $email, $password, $phonenumber, $address, $birthdate,$filename)) {
            header("Location: login.php");
            exit();
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
        <h1>Registration</h1>
        <form class="registration-form" method="POST" enctype="multipart/form-data">
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
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="text" id="phonenumber" name="phonenumber" required>
            </div>
            <div class="form-group">
                <label for="birthdate">Birth Date:</label>
                <input type="date" id="birthdate" name="birthdate" required>
            </div>
            <div class="form-group">
                <label for="address">Address (optional):</label>
                <input type="text" id="address" name="address">
            </div>
            <div class="form-group">
                <label for="picture">Picture (optional)</label>
                <input type="file" name="uploadimage">
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        <p>Already a member? <a href="login.php">Login here</a></p>
        <p>Register as Driver? <a href="register_driver.php">Register here!</a></p>
    </div>

</body>
</html>

