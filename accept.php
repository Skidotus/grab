<?php 
require "db.php";
session_start();
if (!isset($_SESSION["dremail"])) {
    header("Location: driver_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>
        Booking Accepted!
    </h1>
    
</body>
</html>