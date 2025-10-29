<?php
require "db.php";
session_start();

if (!isset($_SESSION['dremail'])) {
    header("Location: driver_login.php");
    exit;
}

$user = selectDriverByEmail($_SESSION['dremail']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="styles/profile.css">
</head>
<body>
    <div class="profile-box">
        <h2>My Profile</h2>
        <p><strong>Name:</strong> <?php echo $user['Driver_Name']; ?></p>
        <p><strong>Email:</strong> <?php echo $user['Driver_Email']; ?></p>
        <p><strong>Address:</strong> <?php echo $user['Driver_Address']; ?></p>
        <p><strong>Phone Number:</strong> <?php echo $user['Driver_Phone']; ?></p>
        <p><strong>Birthdate:</strong> <?php echo $user['Driver_Birthdate']; ?></p>

        <div class="btn-box">
            <a href="driver_edit_profile.php" class="btn">Edit</a>
            <a href="driver_dashboard.php" class="btn logout">Back</a>
        </div>
    </div>
</body>
</html>
