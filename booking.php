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

<body class="bg-gray-600">
    <div class="bg-white w-auto h-auto  mx-8 my-5  p-2 shadow-lg rounded-2xl border-gray-50 flex flex-col gap-2">
        <div>
            <h1 class="bg-gray-100 text-center text-2xl rounded-lg">Booking</h1>
        </div>


        <div id="map" class="m-1 p-1 w-auto h-100 border-2 rounded-2xl"></div>

        <button onclick="getLocation()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Click
            for your current location</button>
        <p class="bg-amber-50 flex w-40 h-45" id="coords"></p>

        <div>
            <form>
                <label for="fname">First name:</label><br>
                <input type="text" id="fname" name="fname"><br>
                <label for="lname">Last name:</label><br>
                <input type="text" id="lname" name="lname">
            </form>

        </div>
    </div>


    <!-- script below -->
    <script>
        const tempat = document.getElementById("coords");

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(success, error);
            }
            else {
                tempat.innerHTML = "Geolocation is not supported by this browser"
            }
        }

        function success(position) {
            tempat.innerHTML = "Latitude: " + position.coords.latitude + "<br>Longitude: " + position.coords.longitude;

        }
        function error() {
            alert("No position available");
        }


        const map = new maplibregl.Map({
            style: 'https://tiles.openfreemap.org/styles/liberty',
            center: [101.7980, 2.934],

            //101.6841 ,101.6841 ,3.1319
            zoom: 16,
            container: 'map',
        })
    </script>

</body>

</html>