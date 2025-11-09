<?php
require "db.php";
require "fareMaker.php";
session_start();
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
}

if ($_POST) {

    $user_ID_stmt = "SELECT User_ID FROM user_creds WHERE User_Email = '" . $_SESSION["email"] . "'";
    $result = $conn->query($user_ID_stmt);
    $user = $result->fetch_assoc();
    $user_id = $user['User_ID'];

    $pickupLocation = $_POST["pickupLocation"];
    $pickupCoords = $_POST["pickupCoords"];
    $dropoffLocation = $_POST["dropoffLocation"];
    $dropoffCoords = $_POST["dropoffCoords"];

    $current_user_coords = $pickupCoords;

    // to slice string

    $pickup = explode(",", $pickupCoords);
    $dropoff = explode(",", $dropoffCoords);

    $pickupLat = $pickup[0];
    $pickupLng = $pickup[1];

    $dropoffLat = $dropoff[0];
    $dropoffLng = $dropoff[1];

    // Insert booking into database
    $email = $_SESSION["email"];
    create_booking($user_id, $current_user_coords, $pickupCoords, $dropoffCoords, $pickupLocation, $dropoffLocation);
    $distance = $_POST['distance']/1000; // convert to km
    $fare = ceil(calculateFare($distance));
}



// echo "<h2>Lat:$pickup[0] Lng:$pickup[1]</h2>";


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finding Driver</title>

    <link href="styles/output.css" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="180x180" href="ico/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="ico/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="ico/favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">
</head>

<body class="bg-gray-200 text-black ">
    <div class="mt-2 mx-2 h-screen items-center justify center flex flex-wrap flex-col  ">


        <div class="bg-white  flex-col flex-wrap  h-screen w-3xl m-20 p-10 rounded-lg shadow-2xl">
            <div class="flex-row flex-wrap gap-2 justify-end">
                <h1 class="flex text-2xl">Booking confirmed</h1>
                <h1 class="flex text-2xl">Waiting for driver</h1>
            </div>
            <br>
            <div class="flex-row ">
                <h1 class="flex text-xl">Distance</h1>
                <?php echo "<h1 class='flex text-xl'>$distance km</h1>"; ?>
            </div>
            <div class="flex-row ">
                <h1 class="flex text-xl">Fare</h1>
                <?php echo "<h1 class='flex text-xl'>RM $fare</h1>"; ?>
            </div>
            <div class="flex-row ">
                <h1 class="flex text-xl">Pickup Location</h1>
                <?php echo "<h1 class='flex text-xl'>$pickupLocation</h1>"; ?>
            </div>

            <div class="flex-col flex-wrap">
                <?php echo "<h1 class='text-xl'>Lat:$pickup[0] Lng:$pickup[1]</h2>"; ?>
            </div>
            <br>
            <div class="flex-row ">
                <h1 class="flex text-xl">Dropoff Location</h1>
                <?php echo "<h1 class='flex text-xl'>$dropoffLocation</h1>"; ?>
            </div>

            <div class="flex-col flex-wrap">
                <?php echo "<h1 class='text-xl'>Lat:$dropoff[0] Lng:$dropoff[1]</h2>"; ?>
            </div>

            <div>
                <h1>Please wait while we find a driver for you...</h1>
                <br>
                
                <form action="cancel_booking.php" method="POST" >
                    <button type="submit" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Cancel Booking</button>
                </form>
                <form action="user_bookings.php" method="POST" >
                    <button type="submit" class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 shadow-lg shadow-green-500/50 dark:shadow-lg dark:shadow-green-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Continue</button>
                </form>
                
            </div>
        </div>


    </div>



</body>

</html>