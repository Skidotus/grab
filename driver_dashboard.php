<?php
require "db.php";
session_start();
if (!isset($_SESSION["email"])) {
    header("Location: driver_login.php");
    exit;
}

$pending_bookings = select_pending_bookings();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver's Dashboard</title>
    <link href="./styles/output.css" rel="stylesheet">
</head>

<body>
    <div class=" w-auto my-5 mx-8 p-2 shadow-lg rounded-2xl border-gray-50 flex gap-2">
        <div class="flex-1 p-2 flex flex-col text-gray-500 border-2 border-gray-200 rounded-2xl">
            <?php
            echo '
            <a class="rounded-2xl my-2 p-2 hover:bg-gray-100" href="driver_profile.php?id=">Driver Profile</a>
            <a class="rounded-2xl my-2 p-2 hover:bg-gray-100" href="driver_booking.php?id=">Driver Booking</a>
            <a class="rounded-2xl my-2 p-2 hover:bg-gray-100" href="">Help</a>
            ';
            ?>
        </div>
        <div class="flex-2 p-2 flex text-gray-500 border-2 border-gray-200 rounded-2xl content-around">
            <?php
            echo '
            <a class="text-center flex-1 rounded-2xl my-2 p-2 hover:bg-gray-100" href="driver_profile.php?id=">Total Earnings</a>
            <a class="text-center flex-1 rounded-2xl my-2 p-2 hover:bg-gray-100" href="driver_booking.php?id=">Total Trip</a>
            <a class="text-center flex-1 rounded-2xl my-2 p-2 hover:bg-gray-100" href="">Distance Travelled</a>
            <a class="text-center flex-1 rounded-2xl my-2 p-2 hover:bg-gray-100" href="">RM/km</a>
            ';
            ?>
        </div>

    </div>
    <div class="w-auto m-8 p-1 shadow-lg rounded-2xl border-1 border-gray-200">
        <div class="p-2">
            <h1 class="text-center text-2xl text-gray-500"> Available Bookings</h1>

        </div>
    </div>
  
    <!-- New div card for bookings-->

    <div class=" mx-8  flex flex-col gap-2 w-auto h-auto ">
        <?php
        foreach ($pending_bookings as $booking) {
            echo '<div class="border-2 rounded-lg p-2 my-2 h-auto shadow-lg ">';
            echo '<div class="border-2 m-2 p-2 rounded-lg flex flex-col">' . '<h1>Customer ID:</h1>' . $booking['user_ID'] . "</div>";
            echo '<div class="p-2 m-2 border-2 rounded-lg ">'.'<h1>Current Location:'.$booking['user_location'].'</h1>'.'</div>';
            echo '<div class="p-2 m-2 border-2 rounded-lg ">'.'<h1>Pickup Location:'.$booking['pickup_location'].'</h1>'.'</div>';
            echo '<div class="p-2 m-2 border-2 rounded-lg ">'.'<h1>Dropoff Location:'.$booking['dropoff_location'].'</h1>'.'</div>';
            echo '<div class="p-2 m-2 flex justify-end">'.'<a  class="border-2 rounded-lg bg-amber-300 w-max p-2 m-2 flex justify-right" href="accept.php?booking_id='.$booking['booking_id'].'">'.'Accept'.'</a>'.'</div>';
            echo '</div>';

        }
        ?>
    </div>


</body>

</html>