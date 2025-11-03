<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <title>OSRM route -> MapLibre</title>
    <link href="https://unpkg.com/maplibre-gl/dist/maplibre-gl.css" rel="stylesheet" />
    <script src="https://unpkg.com/maplibre-gl/dist/maplibre-gl.js"></script>
    <style>
        body,
        html,
        #map {
            height: 100%;
            margin: 0;
            padding: 0
        }

        #steps {
            position: absolute;
            right: 12px;
            top: 12px;
            max-width: 320px;
            background: #fff;
            padding: 8px;
            border-radius: 6px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, .15);
            overflow: auto;
            max-height: 60vh
        }

        .step {
            padding: 6px 4px;
            border-bottom: 1px solid #eee;
            font-size: 13px
        }
    </style>
</head>

<body>
    <div id="map"></div>
    <div id="steps">Loading route...</div>

    <script>
        const map = new maplibregl.Map({
            container: 'map',
            style: 'https://tiles.openfreemap.org/styles/liberty',
            center: [101.79801138335985, 2.9335262287839843],
            zoom: 16
        });

        const osrmUrl = 'http://router.project-osrm.org/route/v1/driving/101.79829494749568,2.9353182737674843;101.7979411283062,2.9310377191034647?steps=true&geometries=geojson&overview=full';

        async function loadRoute() {
            try {
                const res = await fetch(osrmUrl);
                if (!res.ok) throw new Error('Network response not ok: ' + res.status);
                const data = await res.json();
                if (data.code !== 'Ok') throw new Error('OSRM error: ' + data.code);

                const route = data.routes[0];
                const geom = route.geometry; // GeoJSON LineString (coordinates are [lon,lat])
                const steps = route.legs.flatMap(leg => leg.steps);

                // Add route source/layer
                if (map.getSource('osrm-route')) {
                    map.getSource('osrm-route').setData(geom);
                } else {
                    map.addSource('osrm-route', { type: 'geojson', data: geom });
                    map.addLayer({
                        id: 'osrm-route-line',
                        type: 'line',
                        source: 'osrm-route',
                        layout: { 'line-join': 'round', 'line-cap': 'round' },
                        paint: { 'line-width': 6 }
                    });
                }

                // Add start/end markers
                const start = geom.coordinates[0];
                const end = geom.coordinates[geom.coordinates.length - 1];
                new maplibregl.Marker({ color: 'green' }).setLngLat(start).addTo(map);
                new maplibregl.Marker({ color: 'red' }).setLngLat(end).addTo(map);

                // Fit map to route bbox
                const lons = geom.coordinates.map(c => c[0]);
                const lats = geom.coordinates.map(c => c[1]);
                const bounds = [
                    [Math.min(...lons), Math.min(...lats)],
                    [Math.max(...lons), Math.max(...lats)]
                ];
                map.fitBounds(bounds, { padding: 50 });

                // Render steps
                const stepsEl = document.getElementById('steps');
                stepsEl.innerHTML = '<strong>Turn-by-turn</strong>';
                steps.forEach((s, i) => {
                    const div = document.createElement('div');
                    div.className = 'step';
                    // step.maneuver has instruction + location
                    const instr = s.maneuver.instruction || s.name || s.mode || 'Continue';
                    const dist = (s.distance / 1000).toFixed(2) + ' km';
                    div.innerHTML = `<div><b>${i + 1}.</b> ${instr}</div><div style="color:#666;font-size:12px">${dist} • ${s.duration.toFixed(0)}s</div>`;
                    stepsEl.appendChild(div);
                });

            } catch (err) {
                document.getElementById('steps').innerText = 'Error: ' + err.message;
                console.error(err);
            }
        }

        map.on('load', loadRoute);
    </script>
</body>

</html>