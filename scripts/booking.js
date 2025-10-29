// -------------------- Elements --------------------
const tempat = document.getElementById("coords"); // used to show geolocation message (optional)
const input = document.getElementById('address-input');
const datalist = document.getElementById('suggestions-container'); // can be <datalist> or <datalist>

// -------------------- Geolocation --------------------
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(success, error);
    } else {
        tempat.innerHTML = "Geolocation is not supported by this browser";
    }
}

function success(position) {
    const current_coords = [position.coords.longitude, position.coords.latitude];

    new maplibregl.Marker({ color: '#4927F5' })
        .setLngLat(current_coords)
        .setPopup(new maplibregl.Popup({ offset: 25 }).setHTML('<h3>Current</h3>'))
        .addTo(map);

    // set input with lat/lng for form
    input.value = "Latitude:" + position.coords.latitude + " Longitude:" + position.coords.longitude;
}

function error() {
    alert("No position available");
}

// -------------------- Map init --------------------
const map = new maplibregl.Map({
    style: 'https://tiles.openfreemap.org/styles/liberty',
    center: [101.7980, 2.934],
    zoom: 16,
    container: 'map',
});

const markerCoords = [101.79947305762809, 2.935063798197489];

map.on('load', () => {
    // initial marker
    new maplibregl.Marker({ color: '#FF0000' })
        .setLngLat(markerCoords)
        .setPopup(new maplibregl.Popup({ offset: 25 }).setHTML('<h3>A2</h3>'))
        .addTo(map);

    // navigation control
    map.addControl(new maplibregl.NavigationControl({
        visualizePitch: true,
        visualizeRoll: true,
        showZoom: true,
        showCompass: true
    }));
});

// -------------------- Search suggestions (with async debounce + abort) --------------------

// debounce that returns a Promise and works with async funcs
function debounceAsync(func, delay = 300) {
    let timer = null;
    return (...args) => {
        if (timer) clearTimeout(timer);
        return new Promise((resolve, reject) => {
            timer = setTimeout(async () => {
                try {
                    const res = await func(...args);
                    resolve(res);
                } catch (err) {
                    reject(err);
                }
            }, delay);
        });
    };
}

// keep track of in-flight fetch so we can abort it
let currentFetchController = null;

// searchAddress returns first coordinates [lng, lat] or null
const searchAddress = async (query) => {
    // clear previous suggestions
    datalist.innerHTML = '';

    if (!query || query.length < 3) return null;

    // abort previous fetch
    if (currentFetchController) currentFetchController.abort();
    currentFetchController = new AbortController();
    const signal = currentFetchController.signal;

    const url = `https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&limit=7`;

    try {
        const response = await fetch(url, { signal });
        if (!response.ok) throw new Error('Network response not ok');

        const data = await response.json();
        if (!data.features || data.features.length === 0) return null;

        let firstCoords = null;

        data.features.forEach((feature, i) => {
            const props = feature.properties || {};
            const geom = feature.geometry || {};
            const coords = geom.coordinates || [];

            const displayAddress = [
                props.name,
                props.street,
                props.city,
                props.state,
                props.country,
                coords[0],
                coords[1]
            ].filter(Boolean).join(', ');

            const option = document.createElement('option');
            option.value = displayAddress;

            // store coords so we can retrieve them on change/select
            if (coords.length >= 2) {
                option.dataset.lng = coords[0];
                option.dataset.lat = coords[1];
            }

            datalist.appendChild(option);

            if (i === 0 && coords.length >= 2) firstCoords = coords; // [lng, lat]
        });

        return firstCoords;
    } catch (err) {
        if (err.name === 'AbortError') {
            // expected when we cancel previous requests; treat as no result
            return null;
        }
        console.error('Error fetching data:', err);
        return null;
    }
};

// -------------------- Marker management --------------------
let pickupMarker = null;
function handleCoords(coords) {
    if (!coords || coords.length < 2) return;
    // coords is [lng, lat]
    if (pickupMarker) {
        pickupMarker.setLngLat(coords);
    } else {
        pickupMarker = new maplibregl.Marker({ color: '#260399' })
            .setLngLat(coords)
            .setPopup(new maplibregl.Popup({ offset: 25 }).setHTML('<h3>Pickup</h3>'))
            .addTo(map);
    }
    map.flyTo({ center: coords, zoom: 16 });

    // set the input string for the form
    input.value = `Latitude:${coords[1]} Longitude:${coords[0]}`;
}

// -------------------- Wire debounce + input --------------------
const debouncedSearch = debounceAsync(searchAddress);

input.addEventListener('input', (e) => {
    debouncedSearch(e.target.value)
        .then(coords => {
            if (coords) handleCoords(coords);
        })
        .catch(err => {
            // real errors (not abort) will be logged here
            console.error('Search error:', err);
        });
});

// When user picks an option from the datalist (or types exact value), get the saved coords
input.addEventListener('change', (e) => {
    const val = e.target.value;
    // find option that matches the input value
    const options = Array.from(datalist.options || []);
    const found = options.find(opt => opt.value === val);

    if (found && found.dataset.lng && found.dataset.lat) {
        handleCoords([parseFloat(found.dataset.lng), parseFloat(found.dataset.lat)]);
    }
});
