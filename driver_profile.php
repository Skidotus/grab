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
<html lang="en" class="bg-gray-950 text-white">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Profile | Student Transport</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex flex-col">

    <!-- HEADER -->
    <header class="bg-gradient-to-r from-amber-500 to-orange-600 shadow-lg py-4 px-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold tracking-wide flex items-center gap-2">
            🚖 Driver Profile
        </h1>
        <div class="text-right">
            <p class="text-sm text-gray-200">Logged in as</p>
            <p class="font-semibold text-lg text-white"><?php echo htmlspecialchars($_SESSION['dremail']); ?></p>
        </div>
    </header>

    <!-- MAIN -->
    <main class="flex-grow container mx-auto p-8">
        <div class="bg-gray-900 rounded-2xl shadow-xl p-8 max-w-2xl mx-auto text-center">

            <!-- Profile Picture -->
            <div class="flex flex-col items-center mb-6">
                <img src="./image/<?php echo htmlspecialchars($user['Driver_Picture']); ?>"
                     alt="Profile Picture"
                     class="w-32 h-32 object-cover rounded-xl border-2 border-amber-500 shadow-md mb-4">
                <p class="text-gray-400 text-sm">Driver ID: <?php echo htmlspecialchars($user['Driver_ID']); ?></p>
            </div>

            <!-- Info -->
            <h2 class="text-2xl font-bold text-amber-400 mb-6">My Profile</h2>
            <div class="space-y-4 text-left text-gray-300">
                <p><span class="font-semibold text-white">Name:</span> <?php echo htmlspecialchars($user['Driver_Name']); ?></p>
                <p><span class="font-semibold text-white">Email:</span> <?php echo htmlspecialchars($user['Driver_Email']); ?></p>
                <p><span class="font-semibold text-white">Address:</span> <?php echo htmlspecialchars($user['Driver_Address']); ?></p>
                <p><span class="font-semibold text-white">Phone Number:</span> <?php echo htmlspecialchars($user['Driver_Phone']); ?></p>
                <p><span class="font-semibold text-white">Birthdate:</span> <?php echo htmlspecialchars($user['Driver_Birthdate']); ?></p>
            </div>

            <!-- Buttons -->
            <div class="mt-8 flex justify-center gap-4">
                <a href="driver_edit_profile.php"
                   class="px-6 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold transition shadow-md">
                    ✏️ Edit Profile
                </a>
                <a href="driver_dashboard.php"
                   class="px-6 py-2 rounded-xl bg-gray-700 hover:bg-gray-600 text-white font-semibold transition shadow-md">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-gray-500 text-center py-3 text-sm mt-auto">
        © <?php echo date('Y'); ?> Student Transport | Driver Portal
    </footer>

</body>
</html>
