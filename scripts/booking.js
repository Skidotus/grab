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
    borangPickup.value = "Latitude:" + position.coords.latitude + " Longitude:" + position.coords.longitude;
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




// <-----THIS BELOW IS FOR SEARCH SUGGESTIONS------>
// script for search suggestions
// Below for delay search typing
function debounce(func, delay = 300) {
    let timer;
    //below to allow multiple argumes(by typing of course its not going to be finite)
    return (...args) => {
        //below untuk if everytime dia type, time reset.
        clearTimeout(timer);
        timer = setTimeout(() => func.apply(this, args), delay);
    };


}

// get the html id of search input and also unordered list
const input = document.getElementById('address-input');
const datalist = document.getElementById('suggestions-container');

// lepastu baru fetch the API and by using the function debounce to request it
// set async to let function call and get all the file first then baru run
const searchAddress = async (query) => {
    //clearkan dlu previous result
    datalist.innerHTML = '';
    //minimum kena ada 3 query utk return
    if (query.length < 3) return;

    //baru buat API query utk send
    const url = `https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&limit=7`;

    // and then for error handling dekat bawah
    try {
        // set URL response code from fetch method on the url string dekat atas
        const response = await fetch(url);
        //error handling
        if (!response.ok) throw new Error('Network error');
        // wait for payload to fully load
        const data = await response.json();


        //below to debug cooridnates

        // const firstFeature = data.features && data.features[0];
        // const coords = firstFeature.geometry.coordinates; // [lon, lat]
        // const lon = coords[0];
        // const lat = coords[1];

        // console.log('lon:', lon, 'lat:', lat);



        // once payload dah load, baru retrieve the features; strt add, coords
        data.features.forEach(feature => {
            const properties = feature.properties;
            const geometry =feature.geometry;

            // address pulak bawah ni
            const displayAddress = [
                properties.name,
                properties.street,
                properties.city,
                properties.state,
                properties.country,
                geometry.coordinates[0],
                geometry.coordinates[1]
            ].filter(Boolean).join(', ');


            // this if we use datalist instead of container
            const option = document.createElement('option');
            option.value = displayAddress;

            // Storing as data attributes of the option
            option.dataset.lat = geometry.coordinates[0];
            option.dataset.lon = geometry.coordinates[1];

            // console.log(option.dataset.lat,option.dataset.lon);


            datalist.appendChild(option);
        });

    } catch (error) {
        console.error('Error fetching data: ', error)
    }
};

// bawah ni to connect the search Address function to debounce
const debouncedSearch = debounce(searchAddress);

// for DOM manipulation below
input.addEventListener('input', (event) => {
    debouncedSearch(event.target.value);
});

input.addEventListener('change', () => {
    const selectedValue = input.value;
    let selectedOption = null;

    // Find the matching option in the datalist
    // Iterate through the options collection provided by the datalist element
    for (const option of datalist.options) {
        if (option.value === selectedValue) {
            selectedOption = option;
            break;
        }
    }

    if (selectedOption) {
        // Retrieve stored coordinates (now safe because selectedOption exists)
        const lat = selectedOption.dataset.lat;
        const lon = selectedOption.dataset.lon;

        console.log(lat, lon)

        // Ensure data attributes actually contain numbers before parsing
        if (!lat || !lon || isNaN(parseFloat(lat)) || isNaN(parseFloat(lon))) {
            console.error("Coordinates missing or invalid for this selection.");
            // Reset the form input to prevent submitting bad data
            pickupInput.value = selectedValue;
            return; // Stop execution here
        }

        // 3. Update the form field with the structured data
        pickupInput.value = selectedValue + ` (Lat: ${lat}, Lon: ${lon})`;

        // 4. Map update logic (Now safe because we validated lat/lon)
        const newCoords = [parseFloat(lon), parseFloat(lat)];

        // You should probably remove previous markers before adding a new one for selected location
        // Example: If you track markers, remove the old one here.

        new maplibregl.Marker({ color: '#00FF00' })
            .setLngLat(newCoords)
            .setPopup(new maplibregl.Popup({ offset: 25 }).setHTML('<h3>Selected Location</h3>'))
            .addTo(map);

        map.setCenter(newCoords);
        console.log('Selected Coords:', lat, lon);

    } else {
        // 5. Handles cases where the user types custom text (the source of your NaN error)
        console.log("Custom text entered or no valid selection made. Setting form field to plain text.");
        // We set the form input to the plain text the user typed
        pickupInput.value = selectedValue;

        // And importantly: we DON'T try to update the map, which prevents the NaN error.
    }
});
