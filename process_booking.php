<?php
require "db.php";
session_start();
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
}

if ($_POST) {
    $pickupLocation = $_POST["pickupLocation"];
    $pickupCoords = $_POST["pickupCoords"];
    $dropoffLocation = $_POST["dropoffLocation"];
    $dropoffCoords = $_POST["dropoffCoords"];

    // to slice string

    $pickup = explode(",", $pickupCoords);
    $dropoff = explode(",", $dropoffCoords);
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
                <h1 class="flex text-xl">Pickup Location</h1>
                <?php echo "<h1 class='flex text-xl'>$pickupLocation</h1>"; ?>
            </div>

            <div class="flex-col flex-wrap">
                <?php echo "<h1 class='text-xl'>Lat:$pickup[0] Lng:$pickup[1]</h2>"; ?>
            </div>
        </div>


    </div>



</body>

</html>