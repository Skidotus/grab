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
    <title>Booking New</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    <link href="styles/output.css" rel="stylesheet">

    <link href="https://unpkg.com/maplibre-gl/dist/maplibre-gl.css" rel="stylesheet" />
    <script src="https://unpkg.com/maplibre-gl/dist/maplibre-gl.js"></script>


    <link rel="apple-touch-icon" sizes="180x180" href="ico/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="ico/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="ico/favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        #map {
            position: absolute;
            inset: 0;
            z-index: 0;
        }
    </style>
</head>

<body>

    <div id="map"></div>
    <div class="absolute top-6 left-6  bg-white/90 backdrop-blur-sm p-6  rounded-2xl shadow-xl w-80 z-10">

        <h2 class="text-xl font-semibold text-gray-800 text-center mb-4">Booking Form</h2>



        <form id="pickupForm" class="space-y-3 ">
            <!-- Name -->
            <input type="text" id="name" placeholder="Your name"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required />

            <!-- Pickup location div -->

            <div class="w-full border bg-green-700 border-gray-300 rounded-lg px-3 py-2 space-y-3">
                <p class="text-white px-3 py-2">Pickup</p>
                <!-- Pickup Location Input -->
                <input type="text" id="pickupLocation" placeholder="Pickup Location"
                    class="w-full border bg-white  border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required />

                <!-- Get My Location Button -->
                <button type="button" onclick="getLocation()"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg transition">
                    Get My Location
                </button>

                <!-- Toggle Button -->
                <button type="button" id="togglePresets"
                    class="w-full bg-gray-800 hover:bg-gray-900 text-white font-medium py-2 rounded-lg transition">
                    Choose Preset Locations
                </button>

                <!-- Preset Buttons (hidden by default) -->
                <div id="presetContainer" class="hidden flex flex-wrap gap-2 justify-center mt-2">
                    <div id="presetList">

                    </div>
                </div>





                <!-- Coordinates -->
                <input type="text" id="pickupCoords" placeholder="Coordinates"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 text-gray-700" readonly />
            </div>


            <!-- Dropoff Location -->

            <!-- Dropoff Location -->
            <div class="w-full border bg-blue-700 border-gray-300 rounded-lg px-3 py-2 space-y-3">
                <p class="text-white px-3 py-2">Dropoff</p>
                <input type="text" id="dropoffLocation" class="w-full border bg-white  border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Dropoff Location" ... />

                <button type="button" id="togglePresetsDropoff"
                    class="w-full bg-gray-800 hover:bg-gray-900 text-white font-medium py-2 rounded-lg transition">
                    Choose Preset Locations
                </button>

                <div id="presetContainerDropoff" class="hidden flex flex-wrap gap-2 justify-center mt-2">
                    <div id="presetListDropoff"></div>
                </div>

                <input type="text" id="dropoffCoords" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 text-gray-700" placeholder="Coordinates" readonly ... />
            </div>
            <!-- Submit Button -->

            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 rounded-lg transition">
                Get Ride
            </button>
        </form>
    </div>
    <!-- Below script reference -->
    <script>

        document.addEventListener('DOMContentLoaded', () => {
            // DOM elements
            const tempatAmik = document.getElementById('pickupCoords');
            const tempatTurun = document.getElementById('dropoffCoords');
            const pickupInput = document.getElementById('pickupLocation');
            const dropoffInput = document.getElementById('dropoffLocation');
            const presetListPickup = document.getElementById('presetList'); // pickup list (existing id)
            const presetListDropoff = document.getElementById('presetListDropoff'); // dropoff list (new id)
            const togglePickup = document.getElementById('togglePresets'); // pickup toggle
            const toggleDropoff = document.getElementById('togglePresetsDropoff'); // dropoff toggle
            const presetContainerPickup = document.getElementById('presetContainer'); // pickup container
            const presetContainerDropoff = document.getElementById('presetContainerDropoff'); // dropoff container
            const form = document.getElementById('pickupForm');

            // Initialize map
            const map = new maplibregl.Map({
                container: 'map',
                style: 'https://tiles.openfreemap.org/styles/liberty',
                center: [101.79801138335985, 2.9335262287839843],
                zoom: 16
            });

            let pickupMarker = null;
            let dropoffMarker = null;

            // Geolocation logic (for pickup "Get My Location" button)
            window.getLocation = function () {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(success, error);
                } else {
                    if (tempatAmik) tempatAmik.value = 'Geolocation not supported';
                }
            };

            function success(position) {
                const lng = position.coords.longitude;
                const lat = position.coords.latitude;
                if (tempatAmik) tempatAmik.value = `${lat.toFixed(5)}, ${lng.toFixed(5)}`;

                map.flyTo({ center: [lng, lat], zoom: 15, padding: { bottom: 250 } });

                if (pickupMarker) {
                    pickupMarker.setLngLat([lng, lat]);
                } else {
                    pickupMarker = new maplibregl.Marker({ color: 'red' }).setLngLat([lng, lat]).addTo(map);
                }
            }

            function error() {
                if (tempatAmik) tempatAmik.value = 'Unable to get location';
            }

            // Set pickup (fills pickup input + coords + move marker)
            window.setPickup = function (name, lat, lng) {
                if (pickupInput) pickupInput.value = name;
                if (tempatAmik) tempatAmik.value = `${lat.toFixed(5)}, ${lng.toFixed(5)}`;

                map.flyTo({ center: [lng, lat], zoom: 18, padding: { bottom: 300 } });

                if (pickupMarker) pickupMarker.setLngLat([lng, lat]);
                else pickupMarker = new maplibregl.Marker({ color: 'red' }).setLngLat([lng, lat]).addTo(map);
            };

            // Set dropoff (fills dropoff input + coords + set separate dropoff marker)
            window.setDropoff = function (name, lat, lng) {
                if (dropoffInput) dropoffInput.value = name;
                if (tempatTurun) tempatTurun.value = `${lat.toFixed(5)}, ${lng.toFixed(5)}`;

                // optionally move map a bit so marker is visible above bottom overlay
                map.flyTo({ center: [lng, lat], zoom: 18, padding: { bottom: 300 } });

                if (dropoffMarker) dropoffMarker.setLngLat([lng, lat]);
                else dropoffMarker = new maplibregl.Marker({ color: 'green' }).setLngLat([lng, lat]).addTo(map);
            };

            // Preset locations array (same set used for both pickup & dropoff)
            const locations = [
                { name: 'A1', lat: 2.9353182737674843, lng: 101.79829494749568 },
                { name: 'A2', lat: 2.9349646866440273, lng: 101.799301136001 },
                { name: 'A3', lat: 2.9347611061282444, lng: 101.79955603708898 },
                { name: 'A4', lat: 2.9337057012809873, lng: 101.79988418658749 },
                { name: 'A5', lat: 2.933619983077054, lng: 101.7998010083377 },
                { name: 'KT1', lat: 2.9330815654583393, lng: 101.79793589545373 },
                { name: 'KT2', lat: 2.932952988078193, lng: 101.79848594516999 },
                { name: 'KT3', lat: 2.9326610102225916, lng: 101.79960482678788 },
                { name: 'KT4', lat: 2.932856555033398, lng: 101.7995645792477 },
                { name: 'KT5', lat: 2.9310377191034647, lng: 101.7979411283062 },
                { name: 'Faisal Hassan Halle', lat: 2.933119067191419, lng: 101.79752000420486 }
            ];

            // Helper to create a button node
            function makePresetButton(loc, onClickHandler) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = loc.name;
                btn.className = 'px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 m-1';
                btn.addEventListener('click', () => onClickHandler(loc.name, loc.lat, loc.lng));
                return btn;
            }

            // Populate pickup preset list
            if (presetListPickup) {
                locations.forEach(loc => {
                    const b = makePresetButton(loc, window.setPickup);
                    presetListPickup.appendChild(b);
                });
            } else {
                console.warn('presetList (pickup) not found');
            }

            // Populate dropoff preset list
            if (presetListDropoff) {
                locations.forEach(loc => {
                    const b = makePresetButton(loc, window.setDropoff);
                    presetListDropoff.appendChild(b);
                });
            } else {
                console.warn('presetListDropoff not found');
            }

            // Toggle handlers (pickup)
            if (togglePickup && presetContainerPickup) {
                togglePickup.addEventListener('click', () => {
                    presetContainerPickup.classList.toggle('hidden');
                    togglePickup.textContent = presetContainerPickup.classList.contains('hidden') ? 'Choose Preset Locations' : 'Hide Preset Locations';
                });
            }

            // Toggle handlers (dropoff)
            if (toggleDropoff && presetContainerDropoff) {
                toggleDropoff.addEventListener('click', () => {
                    presetContainerDropoff.classList.toggle('hidden');
                    toggleDropoff.textContent = presetContainerDropoff.classList.contains('hidden') ? 'Choose Preset Locations' : 'Hide Preset Locations';
                });
            }

            // Prevent default form submit if you handle it via JS
            if (form) {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    // collect values and send via fetch/AJAX
                    const payload = {
                        name: document.getElementById('name')?.value || '',
                        pickup: document.getElementById('pickupLocation')?.value || '',
                        pickupCoords: document.getElementById('pickupCoords')?.value || '',
                        dropoff: document.getElementById('dropoffLocation')?.value || '',
                        dropoffCoords: document.getElementById('dropoffCoords')?.value || ''
                    };
                    console.log('Form payload', payload);
                    // TODO: send payload using fetch to your server endpoint
                });
            }
        });
    </script>


    </script>

</body>

</html>