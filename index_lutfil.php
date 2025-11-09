<?php
require "db.php";
session_start();

if (!isset($_SESSION['email'])) {
    header(header: "Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Transport</title>
    <link rel="stylesheet" href="styles/index.css">
</head>

<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <h1>Student Transport</h1>

            <div class="user-info">
                <?php echo "<span>Welcome,". $_SESSION['email']."</span>"; ?>
                
            </div>
        </header>
        <nav class="dashboard-nav">
            <button onclick="window.location.href='booking.html'">Book Transport</button>
            <button onclick="window.location.href='data-display.js'">My Bookings</button>
            <button onclick="window.location.href='profile.php'">Profile</button>
            <button onclick="window.location.href='payment.php'">Make Payment</button>
        </nav>
        <main class="dashboard-main">
            <section class="dashboard-summary">
                <h2>Quick Summary</h2>
                <div class="summary-cards">
                    <div class="card">
                        <h3>Upcoming Ride</h3>
                        <p>No rides booked</p>
                    </div>
                    <div class="card">
                        <h3>Bookings History</h3>
                        <p>0</p>
                    </div>
                    <div class="card">
                        <h3>Account Status</h3>
                        <p>Active</p>
                    </div>
                </div>
            </section>
            <button class="logout">
                <a href="logout.php" class="Logout">Logout</a>

            </button>
        </main>
    </div>
</body>

</html>