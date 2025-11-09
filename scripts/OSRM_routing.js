// ...existing code...
async function loadRoute(map, start, end, onResult) {
    // request geojson geometry and full overview
    const osrmurl = `https://router.project-osrm.org/route/v1/driving/${start[0]},${start[1]};${end[0]},${end[1]}?steps=true&geometries=geojson&overview=full`;
    try {
        const res = await fetch(osrmurl);
        if (!res.ok) throw new Error('Network response not ok: ' + res.status);
        const data = await res.json();
        if (data.code !== 'Ok' || !data.routes || !data.routes.length) throw new Error('OSRM error: ' + data.code);

        const route = data.routes[0];
        const distance = route.distance; // in meters
        const duration = route.duration; // in seconds
        console.log('Route distance:', distance, 'meters');
        console.log('Route duration:', duration, 'seconds');

        const geom = route.geometry; // now a GeoJSON LineString

        // wrap as a Feature so maplibre can consume it
        const routeFeature = {
            type: 'Feature',
            properties: {},
            geometry: geom
        };

        // Add or update route source / layer
        if (map.getSource('osrm-route')) {
            map.getSource('osrm-route').setData(routeFeature);
        } else {
            map.addSource('osrm-route', { type: 'geojson', data: routeFeature });
            map.addLayer({
                id: 'osrm-route-line',
                type: 'line',
                source: 'osrm-route',
                layout: { 'line-join': 'round', 'line-cap': 'round' },
                paint: { 'line-width': 6, 'line-color': '#3b82f6' }
            });
        }

        // Add start/end markers (Marker uses .addTo(map))
        const startCoord = geom.coordinates[0];
        const endCoord = geom.coordinates[geom.coordinates.length - 1];
        new maplibregl.Marker({ color: 'green' }).setLngLat(startCoord).addTo(map);
        new maplibregl.Marker({ color: 'red' }).setLngLat(endCoord).addTo(map);

        // Fit map to route bbox
        const lons = geom.coordinates.map(c => c[0]);
        const lats = geom.coordinates.map(c => c[1]);
        const bounds = [
            [Math.min(...lons), Math.min(...lats)],
            [Math.max(...lons), Math.max(...lats)]
        ];
        map.fitBounds(bounds, { padding: 50 });

        if (typeof onResult === "function") {
            onResult({ distance, duration });
        }

    } catch (err) {
        console.error('loadRoute error', err);
    }
}
// ...existing code...