<?php
require "db.php";
session_start();

if (!isset($_SESSION['dremail'])) {
    header("Location: driver_login.php");
    exit;
}

$pending_bookings = select_pending_bookings();

//$totalearnings
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver's Dashboard</title>
    <link href="./styles/output.css" rel="stylesheet">
</head>

<body class="bg-gray-900 text-white">
    <div class="w-auto my-2 mx-8  p-2 shadow-lg rounded-lg  flex gap-2">
        <div class="flex-1 p-2 flex flex-col border-2 border-gray-200 rounded-lg">
            <?php
            echo '
            <a class="rounded-lg my-2 p-2 hover:bg-gray-600" href="driver_profile.php?id=">Driver Profile</a>
            <a class="rounded-lg my-2 p-2 hover:bg-gray-600" href="driver_booking.php?id=">Driver Booking</a>
            <a class="rounded-lg my-2 p-2 hover:bg-gray-600" href="logout.php">Logout</a>
            ';
            ?>
        </div>
        <div class="flex-2 p-2 flex  border-2 border-gray-200 rounded-lg content-around">
            <?php
            echo '
            <a class="text-center flex-1 rounded-lg my-2 p-2 hover:bg-gray-600" href="driver_profile.php?id=">Total Earnings</a>
            <a class="text-center flex-1 rounded-lg my-2 p-2 hover:bg-gray-600" href="driver_booking.php?id=">Total Trip</a>
            <a class="text-center flex-1 rounded-lg my-2 p-2 hover:bg-gray-600" href="">Distance Travelled</a>
            <a class="text-center flex-1 rounded-lg my-2 p-2 hover:bg-gray-600" href="">RM/km</a>
            ';
            ?>
        </div>

    </div>
    <div class="w-auto mx-10 p-1 shadow-lg rounded-lg border-2 border-gray-200">
        <div class="p-2">
            <h1 class="text-center text-2xl "> Available Bookings</h1>

        </div>
    </div>
  
    <!-- New div card for bookings-->

    <div class=" mx-10 my-2  flex flex-col gap-2 w-auto h-auto ">
        <?php
        foreach ($pending_bookings as $booking) {
            echo '<div class="bg-gray-800 border-2 rounded-lg p-2 my-2 h-auto shadow-lg ">';
            echo '<div class="border-2 m-2 p-2 rounded-lg flex flex-col">' . '<h1>Booking ID:</h1>' . $booking['booking_ID'] . "</div>";
            echo '<div class="border-2 m-2 p-2 rounded-lg flex flex-col">' . '<h1>Customer ID:</h1>' . $booking['user_ID'] . "</div>";
            echo '<div class="p-2 m-2 border-2 rounded-lg ">'.'<h1>Current Location:'.$booking['user_location'].'</h1>'.'</div>';
            echo '<div class="p-2 m-2 border-2 rounded-lg ">'.'<h1>Pickup Location:'.$booking['pickup_location'].'</h1>'.'</div>';
            echo '<div class="p-2 m-2 border-2 rounded-lg ">'.'<h1>Dropoff Location:'.$booking['dropoff_location'].'</h1>'.'</div>';
            echo '<div class="p-2 m-2 flex justify-end">'.'<a  class="border-2 rounded-lg bg-amber-300 w-max p-2 m-2 flex justify-right" href="accept.php?booking_id='.$booking['booking_ID'].'">'.'Accept'.'</a>'.'</div>';
            echo '</div>';

        }
        ?>
    </div>


</body>

</html>
