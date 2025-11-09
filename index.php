<?php
require "db.php";
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Transport Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-b from-neutral-900 via-black to-neutral-900 text-neutral-100 flex flex-col">

    <!-- Header -->
    <header class="border-b border-neutral-800 bg-black/40 backdrop-blur-sm py-4 px-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold tracking-tight">Student Transport</h1>
        <div class="text-neutral-300">
            Welcome, <span class="font-medium text-yellow-300"><?php echo htmlspecialchars($email); ?></span>
        </div>
    </header>

    <!-- Main Container -->
    <div class="flex flex-1">

        <!-- Sidebar -->
        <nav class="w-64 border-r border-neutral-800 bg-black/30 p-6 flex flex-col space-y-3">
            <button onclick="window.location.href='ride_booking.php'" class="rounded-xl px-4 py-3 bg-neutral-800/40 hover:bg-yellow-300 hover:text-black transition font-medium text-left">🚗 Book Transport</button>
            <button onclick="window.location.href='user_bookings.php'" class="rounded-xl px-4 py-3 bg-neutral-800/40 hover:bg-yellow-300 hover:text-black transition font-medium text-left">📋 My Bookings</button>
            <button onclick="window.location.href='profile.php'" class="rounded-xl px-4 py-3 bg-neutral-800/40 hover:bg-yellow-300 hover:text-black transition font-medium text-left">👤 Profile</button>
            <button onclick="window.location.href='payment.php'" class="rounded-xl px-4 py-3 bg-neutral-800/40 hover:bg-yellow-300 hover:text-black transition font-medium text-left">💳 Make Payment</button>
            
            <div class="mt-auto pt-6 border-t border-neutral-800">
                <a href="logout.php" class="inline-flex items-center justify-center w-full rounded-xl border border-neutral-700 px-4 py-2.5 hover:bg-red-500 hover:text-white transition">
                    🔒 Logout
                </a>
            </div>
        </nav>

        <!-- Dashboard Content -->
        <main class="flex-1 p-8 overflow-y-auto">
            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">Quick Summary</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Upcoming Ride -->
                    <div class="rounded-2xl border border-neutral-800 bg-black/30 p-6 shadow-lg hover:shadow-yellow-300/10 transition">
                        <h3 class="text-lg font-semibold mb-2 text-yellow-300">Upcoming Ride</h3>
                        <p class="text-neutral-400">No rides booked</p>
                    </div>
                    <!-- Bookings History -->
                    <div class="rounded-2xl border border-neutral-800 bg-black/30 p-6 shadow-lg hover:shadow-yellow-300/10 transition">
                        <h3 class="text-lg font-semibold mb-2 text-yellow-300">Bookings History</h3>
                        <p class="text-neutral-400">0</p>
                    </div>
                    <!-- Account Status -->
                    <div class="rounded-2xl border border-neutral-800 bg-black/30 p-6 shadow-lg hover:shadow-yellow-300/10 transition">
                        <h3 class="text-lg font-semibold mb-2 text-yellow-300">Account Status</h3>
                        <p class="text-green-400 font-medium">Active</p>
                    </div>
                </div>
            </section>

            <section>
                <div class="rounded-2xl border border-neutral-800 bg-black/30 p-6 shadow-lg">
                    <h3 class="text-lg font-semibold mb-2 text-yellow-300">Need a Ride?</h3>
                    <p class="text-neutral-400 mb-4">Book your next trip easily and manage all your payments right from this dashboard.</p>
                    <button onclick="window.location.href='ride_booking.php'" class="inline-flex items-center justify-center rounded-xl bg-yellow-300 text-black px-5 py-2.5 font-semibold hover:opacity-90 transition">
                        Book Now
                    </button>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
