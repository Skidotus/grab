<?php
require "db.php";
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$message = "";

if ($_POST) {
    $id = $_POST['ID'];
    $username = $_POST['Name'];
    $email = $_POST['Email'];
    $password = $_POST['Password'];

    if (updateByID($id, $username, $email, $password)) {
        $message = "✅ Profile updated successfully!";
        $_SESSION['email'] = $email;
    } else {
        $message = "❌ Failed to update profile.";
    }
}

$user = selectUserByEmail($_SESSION['email']);
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
            <input type="hidden" name="ID" value="<?php echo $user['User_ID']; ?>">

            <label for="User_Name">Name:</label><br>
            <input type="text" name="Name" value="<?php echo htmlspecialchars($user['User_Name']); ?>" required>

            <label for="User_Email"><br>Email:</label><br>
            <input type="email" name="Email" value="<?php echo htmlspecialchars($user['User_Email']); ?>" required>

            <label for="User_Pass"><br>Password:</label><br>
            <input type="password" name="Password" placeholder="Enter new password">

            <div class="btn-box">
                <button type="submit" class="btn">Save</button>
                <a href="profile.php" class="btn logout">Back</a>

            </div>
        </form>
    </div>
</body>
</html>
