<?php
require "db.php";
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$user = selectUserByEmail($_SESSION['email']);
if (!$user) {
    echo "User not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-b from-neutral-900 via-black to-neutral-900 text-neutral-100 flex items-center justify-center p-6">

    <div class="w-full max-w-2xl">
        <div class="rounded-2xl border border-neutral-800 bg-neutral-900/60 shadow-2xl p-8 text-center">

            <h2 class="text-2xl md:text-3xl font-bold mb-6 text-yellow-300">My Profile</h2>

            <div class="flex flex-col items-center mb-6">
                <img src="./image/<?php echo htmlspecialchars($user['User_Picture']); ?>"
                     alt="Profile Picture"
                     class="w-32 h-32 object-cover rounded-xl border border-neutral-700 shadow-lg mb-4">
                <p class="text-neutral-400 text-sm">Profile ID: <?php echo htmlspecialchars($user['User_ID']); ?></p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-left mb-6">
                <div class="rounded-xl border border-neutral-800 bg-black/30 p-4">
                    <p class="text-neutral-400 text-sm">Name</p>
                    <p class="font-medium mt-1"><?php echo htmlspecialchars($user['User_Name']); ?></p>
                </div>
                <div class="rounded-xl border border-neutral-800 bg-black/30 p-4">
                    <p class="text-neutral-400 text-sm">Email</p>
                    <p class="font-medium mt-1 break-all"><?php echo htmlspecialchars($user['User_Email']); ?></p>
                </div>
                <div class="rounded-xl border border-neutral-800 bg-black/30 p-4">
                    <p class="text-neutral-400 text-sm">Address</p>
                    <p class="font-medium mt-1"><?php echo htmlspecialchars($user['User_Address']); ?></p>
                </div>
                <div class="rounded-xl border border-neutral-800 bg-black/30 p-4">
                    <p class="text-neutral-400 text-sm">Phone Number</p>
                    <p class="font-medium mt-1"><?php echo htmlspecialchars($user['User_Phone']); ?></p>
                </div>
                <div class="rounded-xl border border-neutral-800 bg-black/30 p-4 sm:col-span-2">
                    <p class="text-neutral-400 text-sm">Birthdate</p>
                    <p class="font-medium mt-1"><?php echo htmlspecialchars($user['User_Birthdate']); ?></p>
                </div>
            </div>

            <div class="flex items-center justify-center gap-4">
                <a href="edit_profile.php" 
                   class="inline-flex items-center justify-center rounded-2xl bg-yellow-300 text-black px-6 py-2.5 font-semibold hover:opacity-90 transition">
                    Edit Profile
                </a>
                <a href="index.php" 
                   class="inline-flex items-center justify-center rounded-2xl border border-neutral-800 px-6 py-2.5 hover:bg-white/5 transition">
                    Back to Dashboard
                </a>
            </div>

        </div>
    </div>

</body>
</html>
