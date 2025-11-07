<?php
require "db.php";
session_start();

if (!isset($_SESSION['dremail'])) {
    header("Location: driver_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" class="bg-gray-950 text-white">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard | Student Transport</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex flex-col">

    <!-- HEADER -->
    <header class="bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg py-4 px-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold tracking-wide">🚐 Student Transport</h1>
        <div class="text-right">
            <p class="text-sm">Welcome,</p>
            <p class="font-semibold text-lg"><?php echo $_SESSION['dremail']; ?></p>
        </div>
    </header>

    <!-- NAVIGATION -->
    <nav class="bg-gray-800 border-b border-gray-700 flex justify-center space-x-6 py-3">
        <button onclick="window.location.href='booking.html'"
            class="px-4 py-2 rounded-lg bg-indigo-500 hover:bg-indigo-600 transition">Book Transport</button>
        <button onclick="window.location.href='data-display.js'"
            class="px-4 py-2 rounded-lg bg-indigo-500 hover:bg-indigo-600 transition">My Bookings</button>
        <button onclick="window.location.href='driver_profile.php'"
            class="px-4 py-2 rounded-lg bg-indigo-500 hover:bg-indigo-600 transition">Profile</button>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="flex-grow container mx-auto p-6">
        <section class="bg-gray-900 rounded-2xl shadow-xl p-6">
            <h2 class="text-xl font-semibold mb-6 border-b border-gray-700 pb-2">Quick Summary</h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-gray-800 p-5 rounded-xl shadow-md hover:shadow-lg transition">
                    <h3 class="text-lg font-semibold mb-2">Upcoming Ride</h3>
                    <p class="text-gray-400">No rides booked</p>
                </div>
                <div class="bg-gray-800 p-5 rounded-xl shadow-md hover:shadow-lg transition">
                    <h3 class="text-lg font-semibold mb-2">Bookings History</h3>
                    <p class="text-gray-400">0</p>
                </div>
                <div class="bg-gray-800 p-5 rounded-xl shadow-md hover:shadow-lg transition">
                    <h3 class="text-lg font-semibold mb-2">Account Status</h3>
                    <p class="text-green-400 font-semibold">Active</p>
                </div>
            </div>
        </section>

        <!-- LOGOUT BUTTON -->
        <div class="flex justify-center mt-8">
            <a href="logout.php"
                class="px-6 py-2 bg-red-600 hover:bg-red-700 rounded-xl text-white font-semibold shadow-md transition">
                Logout
            </a>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-gray-500 text-center py-3 text-sm">
        © <?php echo date('Y'); ?> Student Transport | Driver Dashboard
    </footer>

</body>
</html>
