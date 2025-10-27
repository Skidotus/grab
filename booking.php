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
        <div>
            <button onclick="getLocation()"
                    class="mx-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Click to pickup at current destination
            </button>

            <form class="flex flex-wrap flex-col" method="POST">
                <label class="flex-1 p-2 mx-2 text-lg " for="pickupDestination">Pickup Location</label><br>
                <input id="pickupInput" class="flex-4 bg-gray-700 p-2 mx-2  rounded-lg " type="text" id="pickupDestination"
                    name="pickupDestination"><br>
                <label class="flex-1 p-2 mx-2 " for="lname">Dropoff Location</label>
                <input  class="flex-4 bg-gray-700 p-2 mx-2 mb-4 rounded-lg" type="text" id="lname" name="lname">
                
                <!-- <p class=" flex w-40 h-45" id="coords"></p> -->
                <div id="map" class="m-2 p-1 w-auto h-96 border-2 rounded-lg"></div>
                <div class="text-white flex justify-end ">
                    <button class=" mx-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Submit</button>

                </div>

                
            </form>

        </div>






    </div>



    <!-- script below -->
    <script>
        const tempat = document.getElementById("coords");
        //below for form input fill
        const borangPickup = document.getElementById("pickupInput")

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(success, error);
            }
            else {
                tempat.innerHTML = "Geolocation is not supported by this browser"
            }
        }

        function success(position) {
            let current_coords = [position.coords.longitude, position.coords.latitude];
            new maplibregl.Marker({ color: '#4927F5' }) // Custom color
                // Set position using the markerCoords variable
                .setLngLat(current_coords)
                // Add a simple popup
                .setPopup(new maplibregl.Popup({ offset: 25 })
                    .setHTML('<h3>Current</h3>'))
                // Add the marker to the map
                .addTo(map);
            borangPickup.value = "Latitude:"+position.coords.latitude+" Longitude:"+position.coords.longitude;
            //tempat.innerHTML = "Latitude: " + position.coords.latitude + "<br>Longitude: " + position.coords.longitude;

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
        });

        const markerCoords = [101.79947305762809, 2.935063798197489]



        map.on('load', () => {




            // Create the marker instance
            new maplibregl.Marker({ color: '#FF0000' }) // Custom color
                // Set position using the markerCoords variable
                .setLngLat(markerCoords)
                // Add a simple popup
                .setPopup(new maplibregl.Popup({ offset: 25 })
                    .setHTML('<h3>A2</h3>'))
                // Add the marker to the map
                .addTo(map);

            // Add navigation control

        });

        map.on('load', () => {
            map.addControl(new maplibregl.NavigationControl({
                visualizePitch: true,
                visualizeRoll: true,
                showZoom: true,
                showCompass: true
            }));

        });


    </script>

</body>

</html>