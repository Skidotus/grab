<?php
require "db.php";
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$user = selectUserByEmail($_SESSION['email']);
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
        <p><strong>Name:</strong> <?php echo $user['User_Name']; ?></p>
        <p><strong>Email:</strong> <?php echo $user['User_Email']; ?></p>
        <p><strong>User ID:</strong> <?php echo $user['User_ID']; ?></p>

        <div class="btn-box">
            <a href="edit_profile.php" class="btn">Edit</a>
            <a href="index.php" class="btn logout">Back</a>
        </div>
    </div>
</body>
</html>
