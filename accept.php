<?php 
require "db.php";
require "fareMaker.php";
session_start();

if (!isset($_SESSION["dremail"])) {
    header("Location: driver_login.php");
    exit;
}

$pickup_coords = "";
$dropoff_coords = "";
$message = "";

if ($_GET) {
    $booking_id = $_GET['booking_id'];
    $driver_email = $_SESSION['dremail'];

    // Get Driver ID
    $driver_ID_stmt = "SELECT Driver_ID FROM drivers WHERE Driver_Email = '$driver_email'";
    $result = $conn->query($driver_ID_stmt);
    $driver = $result->fetch_assoc();
    $driver_id = $driver['Driver_ID'];

    // Get pickup coordinates from booking
    $coords_stmt = "SELECT ST_X(user_pickup_location) AS lon, ST_Y(user_pickup_location) AS lat FROM booking WHERE booking_number = '$booking_id'";
    $coords_result = $conn->query($coords_stmt);
    if ($coords_result && $coords_result->num_rows > 0) {
        $coords = $coords_result->fetch_assoc();
        $pickup_coords = $coords['lat'] . "," . $coords['lon']; // format for map URLs
    }

    // Update booking status to 'accepted' and assign driver
    $stmt = "UPDATE booking SET status = 'accepted' WHERE booking_number = '$booking_id'";
    $trip_stmt = "INSERT INTO trip (booking_number, Driver_ID, created_at) VALUES ('$booking_id', '$driver_id', NOW())";
    $conn->query($trip_stmt);

    if ($conn->query($stmt) === TRUE) {
        $message = "Booking accepted successfully. Trip started.";
    } else {
        $message = "Error updating record: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking Accepted</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-yellow-400 flex flex-col items-center justify-center min-h-screen p-6">

  <div class="bg-neutral-900 border border-yellow-400 rounded-lg p-8 w-full max-w-md text-center shadow-lg">
    <h1 class="text-3xl font-bold mb-4">Booking Accepted!</h1>
    <p class="text-yellow-300 mb-6"><?php echo $message; ?></p>

    <?php if ($pickup_coords): ?>
      <div class="flex flex-col gap-4">
        <a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo $pickup_coords; ?>" 
           target="_blank" 
           class="bg-yellow-400 text-black font-semibold py-2 rounded-lg hover:bg-yellow-300 transition">
           🚗 Navigate with Google Maps
        </a>

        <a href="https://waze.com/ul?ll=<?php echo $pickup_coords; ?>&navigate=yes" 
           target="_blank" 
           class="bg-yellow-400 text-black font-semibold py-2 rounded-lg hover:bg-yellow-300 transition">
           🧭 Navigate with Waze
        </a>
      </div>
    <?php else: ?>
      <p class="text-gray-400 mt-4">No pickup coordinates found.</p>
    <?php endif; ?>

    <a href="driver_dashboard.php" 
       class="mt-6 inline-block border border-yellow-400 text-yellow-400 px-5 py-2 rounded-lg hover:bg-yellow-400 hover:text-black transition">
       Back to Dashboard
    </a>
  </div>
</body>
</html>
