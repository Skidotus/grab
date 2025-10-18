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
    <title>Booking Page</title>
    <script src="https://unpkg.com/maplibre-gl/dist/maplibre-gl.js"></script>
    <link href="https://unpkg.com/maplibre-gl/dist/maplibre-gl.css" rel="stylesheet" />
</head>

<body>
    <div id="map" style="width: 100%; height: 500px"></div>

    <button onclick="getLocation()">Click for your current location</button>
    <p id="coords"></p> 



    <!-- script below -->
    <script>
        const tempat = document.getElementById("coords");

        function getLocation(){
            if(navigator.geolocation){
                navigator.geolocation.getCurrentPosition(success,error);
            }
            else{
                tempat.innerHTML ="Geolocation is not supported by this browser"
            }
        }

        function success(position){
            tempat.innerHTML = "Latitude: " +position.coords.latitude + "<br>Longitude: " + position.coords.longitude;

        }
        function error(){
            alert("No position available");
        }


        const map = new maplibregl.Map({
            style: 'https://tiles.openfreemap.org/styles/liberty',
            center: [3.1319, 101.6841],
            zoom: 9.5,
            container: 'map',
        })
    </script>

</body>

</html>