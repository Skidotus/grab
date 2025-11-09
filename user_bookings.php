<?php
require "db.php";
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION["email"];

// get user id
$user_ID_stmt = "SELECT User_ID FROM user_creds WHERE User_Email = '$email'";
$result = $conn->query($user_ID_stmt);
$user = $result->fetch_assoc();
$user_id = $user['User_ID'];

// get all bookings for that user
$booking_stmnt = "SELECT * FROM booking WHERE User_ID = '$user_id' ORDER BY created_at DESC";
$result = $conn->query($booking_stmnt);

$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Bookings</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-yellow-400 min-h-screen flex flex-col items-center p-6">

  <!-- Header -->
  <div class="w-full max-w-5xl flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold border-b-4 border-yellow-400 pb-2 rounded-lg">My Bookings</h1>
    <a href="index.php" 
       class="bg-yellow-400 text-black font-semibold px-5 py-2 rounded-lg shadow-md hover:bg-yellow-300 hover:scale-105 transition">
        Back to Home
    </a>
  </div>

  <!-- Booking Table -->
  <?php if (!empty($bookings)): ?>
    <div class="w-full max-w-5xl overflow-x-auto rounded-lg border border-yellow-400">
      <table class="w-full rounded-lg text-center text-yellow-300 border-collapse overflow-hidden">
        <thead class="bg-yellow-400 text-black uppercase text-sm font-semibold">
          <tr>
            <th class="px-4 py-3 border-b border-black">Booking Number</th>
            <th class="px-4 py-3 border-b border-black">Pickup Location</th>
            <th class="px-4 py-3 border-b border-black">Dropoff Location</th>
            <th class="px-4 py-3 border-b border-black">Status</th>
            <th class="px-4 py-3 border-b border-black">Booking Time</th>
          </tr>
        </thead>
        <tbody class="rounded-lg">
          <?php foreach ($bookings as $booking): ?>
            <tr class="hover:bg-yellow-400 hover:text-black transition duration-200">
              <td class="px-4 py-3 border-t border-yellow-400"><?php echo $booking['booking_number']; ?></td>
              <td class="px-4 py-3 border-t border-yellow-400"><?php echo $booking['pickup_location']; ?></td>
              <td class="px-4 py-3 border-t border-yellow-400"><?php echo $booking['dropoff_location']; ?></td>
              <td class="px-4 py-3 border-t border-yellow-400 font-semibold"><?php echo $booking['status']; ?></td>
              <td class="px-4 py-3 border-t border-yellow-400"><?php echo $booking['created_at']; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p class="text-yellow-300 border border-yellow-400 rounded-lg px-6 py-4 mt-6">No bookings found.</p>
  <?php endif; ?>
</body>
</html>
