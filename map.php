<?php
require "db.php";
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Pin a Location (OpenFreeMap + MapLibre)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://unpkg.com/maplibre-gl/dist/maplibre-gl.css" rel="stylesheet" />
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        #map {
            height: 100vh;
            width: 100%;
        }

        .coords {
            position: fixed;
            bottom: 12px;
            left: 12px;
            background: #fff;
            padding: 8px 10px;
            border-radius: 8px;
            font: 14px/1.3 system-ui;
        }
    </style>
</head>

<body>
    <div id="map"></div>

    <!-- Optional: shows picked coords and holds hidden inputs for form submit -->
    <div class="coords">
        <strong>Pinned:</strong> <span id="out">none</span>
        <form id="coord-form" action="save.php" method="POST" style="display:none">
            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">
        </form>
    </div>

    <script src="https://unpkg.com/maplibre-gl/dist/maplibre-gl.js"></script>
    <script>
        // 1) Init map (centered on KL)
        const map = new maplibregl.Map({
            container: 'map',
            style: 'https://tiles.openfreemap.org/styles/liberty', // OpenFreeMap official style
            center: [101.6869, 3.1390],
            zoom: 12
        });

        // Optional controls
        map.addControl(new maplibregl.NavigationControl(), 'top-right');

        // 2) Click-to-pin logic
        let marker = null;
        let selectedCoords = null;

        map.on('click', (e) => {
            const { lng, lat } = e.lngLat;

            if (marker) {
                marker.setLngLat([lng, lat]);
            } else {
                marker = new maplibregl.Marker({ draggable: true })
                    .setLngLat([lng, lat])
                    .addTo(map)
                    .on('dragend', () => {
                        const { lng, lat } = marker.getLngLat();
                        updateCoords(lat, lng);
                    });
            }
            updateCoords(lat, lng);
        });

        function updateCoords(lat, lng) {
            selectedCoords = { lat, lng };
            // Show on screen
            document.getElementById('out').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            // Fill hidden inputs for form submit (PHP/n8n)
            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;
            console.log('Pinned location:', selectedCoords);
        }

        // 3) Example: submit to PHP when you have a pin:
        // document.getElementById('coord-form').submit();

        // Or via fetch:
        // fetch('save.php', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify(selectedCoords) });
    </script>
</body>

</html>