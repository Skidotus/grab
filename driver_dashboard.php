<?php 
require "db.php";
session_start();
if (!isset($_SESSION["email"])) {
    header("Location: driver_login.php") ;
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <h1>Driver's Dashboard</h1>

        </header>
    </div>


    
</body>
</html>