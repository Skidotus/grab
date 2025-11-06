<?php
require "db.php";
session_start();

if (!isset($_SESSION['dremail'])) {
    header("Location: driver_login.php");
    exit;
}

$message = "";

if ($_POST) {
    $drid = $_POST['drID'];
    $drusername = $_POST['drName'];
    $dremail = $_POST['drEmail'];
    $drpassword = $_POST['drPassword'];
    $draddress = $_POST['drAddress'];
    $drphonenumber = $_POST['drPhone'];
    $drbirthdate = $_POST['drBirthdate'];

    if (DriverupdateByID($drid, $drusername, $dremail, $drpassword, $draddress, $drbirthdate, $drphonenumber)) {
        $message = "✅ Profile updated successfully!";
        $_SESSION['dremail'] = $dremail;
    } else {
        $message = "❌ Failed to update profile.";
    }
}

$user = selectDriverByEmail($_SESSION['dremail']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="styles/profile.css">
</head>
<body>
    <div class="profile-box">
        <h2>Edit Profile</h2>

        <?php if ($message): ?>
            <div class="<?php echo strpos($message, '✅') !== false ? 'success-msg' : 'error-msg'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form class="update-profile" method="POST">
            <input type="hidden" name="drID" value="<?php echo $user['Driver_ID']; ?>">

            <label for="User_Name">Name:</label><br>
            <input type="text" name="drName" value="<?php echo htmlspecialchars($user['Driver_Name']); ?>" required>
            <br>

            <label for="User_Email"><br>Email:</label><br>
            <input type="email" name="drEmail" value="<?php echo htmlspecialchars($user['Driver_Email']); ?>" required>
            <br>

            <label for="User_Pass"><br>Password:</label><br>
            <input type="password" name="drPassword" placeholder="Enter new password"><br>
            <br>

            <label for="User_Address">Address:</label><br>
            <input type="address" name="drAddress" value="<?php echo htmlspecialchars($user['Driver_Address']); ?>"><br>
            <br>

            <label for="User_Phone">Phone Number:</label><br>
            <input type="phonenumber" name="drPhone" value="<?php echo htmlspecialchars($user['Driver_Phone']); ?>"><br>
            <br>

            <label for="User_Birthdate">Birth Date:</label><br>
            <input type="birthdate" name="drBirthdate" value="<?php echo htmlspecialchars($user['Driver_Birthdate']); ?>">


            <div class="btn-box">
                <button type="submit" class="btn">Save</button>
                <a href="driver_profile.php" class="btn logout">Back</a>

            </div>
        </form>
    </div>
</body>
</html>
