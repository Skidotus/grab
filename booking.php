<?php

require "db.php";
session_start();
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    <link href="styles/output.css" rel="stylesheet">

    <link href="https://unpkg.com/maplibre-gl/dist/maplibre-gl.css" rel="stylesheet" />
    <script src="https://unpkg.com/maplibre-gl/dist/maplibre-gl.js"></script>

    <!-- <style></style> -->

</head>

<body class="bg-gray-900">
    <div
        class="bg-gray-800 text-white w-auto h-auto  mx-8 my-5  p-2 shadow-lg rounded-2xl border-gray-50 flex flex-col gap-2">
        <div>
            <h1 class=" text-center text-2xl rounded-lg">Booking</h1>
        </div>
        <div class="flex flex-col">

            <!-- Below is for search sugggestions -->
            <input class="flex-4 bg-gray-700 p-2 mx-2  rounded-lg w-auto" type="search" id="address-input"
                placeholder="Search for an address...">
            <datalist id="suggestions-container" class="text-white"></datalist>
            <!-- <div id="suggestions-container"></div> -->

            
            <!-- Below button for click at current destination -->
            <button onclick="getLocation()"
                class="my-2 mx-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Click
                to pickup at current destination
            </button>

            <form class="flex flex-wrap flex-col" method="POST">
                <label class="flex-1 p-2 mx-2 text-lg " for="pickupDestination">Pickup Location</label><br>
                <input id="pickupInput" class="flex-4 bg-gray-700 p-2 mx-2  rounded-lg " type="text"
                    id="pickupDestination" name="pickupDestination"><br>
                <label class="flex-1 p-2 mx-2 " for="lname">Dropoff Location</label>
                <input class="flex-4 bg-gray-700 p-2 mx-2 mb-4 rounded-lg" type="text" id="lname" name="lname">

                <!-- <p class=" flex w-40 h-45" id="coords"></p> -->
                <div id="map" class="m-2 p-1 w-auto h-96 border-2 rounded-lg"></div>
                <div class="text-white flex justify-end ">
                    <button
                        class=" mx-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Get
                        Quote</button>

                </div>


            </form>

        </div>






    </div>



    <!-- script below -->
    <script src="scripts/booking.js">
       


    </script>

</body>

</html>