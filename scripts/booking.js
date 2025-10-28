const tempat = document.getElementById("coords");
//below for form input fill
//const borangPickup = document.getElementById("pickupInput")


const input = document.getElementById('address-input');
const datalist = document.getElementById('suggestions-container');

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
    input.value = "Latitude:" + position.coords.latitude + " Longitude:" + position.coords.longitude;
    //tempat.innerHTML = "Latitude: " + position.coords.latitude + "<br>Longitude: " + position.coords.longitude;

}
function error() {
    alert("No position available");
}


const map = new maplibregl.Map({
    style: 'https://tiles.openfreemap.org/styles/liberty',
    //
    center: [101.7980, 2.934],
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

// ----- Debounce that works with async functions and returns a Promise -----
function debounceAsync(func, delay = 300) {
    let timer = null;
    return (...args) => {
        // clear previous debounce timer
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

// ----- shared AbortController to cancel previous fetches -----
let currentFetchController = null;

// ----- returns coordinates (or null) -----
// note: we return the coordinates (e.g. first feature) so caller can use them
const searchAddress = async (query) => {
    datalist.innerHTML = '';
    if (!query || query.length < 3) return null;

    // cancel previous in-flight fetch
    if (currentFetchController) currentFetchController.abort();
    currentFetchController = new AbortController();
    const signal = currentFetchController.signal;

    const url = `https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&limit=7`;

    try {
        const response = await fetch(url, { signal });
        if (!response.ok) throw new Error('Network response not ok');

        const data = await response.json();
        if (!data.features || data.features.length === 0) return null;

        // populate suggestions and pick the coordinates from the first feature
        let firstCoords = null;
        data.features.forEach((feature, i) => {
            const props = feature.properties;
            const geom = feature.geometry;
            const displayAddress = [
                props.name,
                props.street,
                props.city,
                props.state,
                props.country,
                geom.coordinates[0],
                geom.coordinates[1]
            ].filter(Boolean).join(', ');

            //list populate utk suggestions
            const option = document.createElement('option');
            option.value = displayAddress;

            // storing coords data from datalist
            option.dataset.lng = geom.coordinates[0];
            option.dataset.lat = geom.coordinates[1];

            datalist.appendChild(option);

            if (i === 0) firstCoords = geom.coordinates; // lng, lat
        });

        // [lng, lat] or null for data return to the main debounce function
        return firstCoords; 

    } catch (err) {
        if (err.name === 'AbortError') {
            // fetch was aborted because of a newer query — not an actual error
            return null;
        }
        console.error('Error fetching suggestions:', err);
        return null;
    }
};

// ----- marker management and map pan -----
let pickupMarker = null;
function handleCoords(coords) {
    if (!coords) return;
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
    // optionally set some form value:
    input.value = `Latitude:${coords[1]} Longitude:${coords[0]}`;
}

// ----- wire debounce + input -----
// use the async debounce so we can .then the coords
const debouncedSearch = debounceAsync(searchAddress);
input.addEventListener('input', (e) => {
    debouncedSearch(e.target.value)
        .then(coords => {
            if (coords) handleCoords(coords);
        })
        .catch(err => {
            // handle genuine errors if needed
            console.error('Search error:', err);
        });
});

// ----- if you want to allow user to select one of the datalist options -----
// When user chooses an option from the datalist, extract dataset lat/lng from the matched option:
input.addEventListener('change', (e) => {
    const val = e.target.value;
    // try to find option matching the current value
    const options = Array.from(datalist.options || []); // datalist is <datalist> in HTML
    const found = options.find(opt => opt.value === val);
    if (found && found.dataset.lng && found.dataset.lat) {
        handleCoords([parseFloat(found.dataset.lng), parseFloat(found.dataset.lat)]);
    }
});




// function debounce(func, delay = 300) {
//     let timer;
//     //below to allow multiple argumes(by typing of course its not going to be finite)
//     return (...args) => {
//         //below untuk if everytime dia type, time reset.
//         clearTimeout(timer);
//         timer = setTimeout(() => func.apply(this, args), delay);
//     };


// }

// // get the html id of search input and also unordered list


// // lepastu baru fetch the API and by using the function debounce to request it
// // set async to let function call and get all the file first then baru run
// const searchAddress = async (query) => {
//     //clearkan dlu previous result
//     datalist.innerHTML = '';
//     //minimum kena ada 3 huruf baru start cari
//     if (query.length < 3) return;

//     //baru buat API query utk send
//     const url = `https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&limit=7`;
    

//     //outside variable of lat and lon
//     let out_lat = null;
//     let out_lon = null;
//     let out_coords = null;

    

//     // and then for error handling dekat bawah
//     try {
//         // set URL response code from fetch method on the url string dekat atas
//         const response = await fetch(url);
//         //error handling
//         if (!response.ok) throw new Error('Network error');
//         // wait for payload to fully load
//         const data = await response.json();
//         // once payload dah load, baru retrieve the features; strt add, coords
//         data.features.forEach(feature => {
//             const properties = feature.properties;
//             const geometry = feature.geometry;

//             // address pulak bawah ni
//             const displayAddress = [
//                 properties.name,
//                 properties.street,
//                 properties.city,
//                 properties.state,
//                 properties.country,
//                 geometry.coordinates[0],
//                 geometry.coordinates[1]
//             ].filter(Boolean).join(', ');


//             // this if we use datalist instead of container
//             const option = document.createElement('option');
//             option.value = displayAddress;

//             // Storing as data attributes of the option
//             // option.dataset.lat = geometry.coordinates[0];
//             // option.dataset.lon = geometry.coordinates[1];



//             //below to put marker on the map
//             pickupCoords = geometry.coordinates;
//             out_coords = pickupCoords;


            

//             // console.log(pickupCoords);

//             // pickupCoords ? () => {
//             //     new maplibregl.Marker({ color: '#260399' }) // Custom color
//             //         // Set position using the markerCoords variable
//             //         .setLngLat(pickupCoords)
//             //         // Add a simple popup
//             //         .setPopup(new maplibregl.Popup({ offset: 25 })
//             //             .setHTML('<h3>Pickup</h3>'))
//             //         // Add the marker to the map
//             //         .addTo(map);
//             // } : alert("Pickup not detected");





//             // console.log(option.dataset.lat,option.dataset.lon);
//             datalist.appendChild(option);
//         });



//     } catch (error) {
//         console.error('Error fetching data: ', error)
//     }

//     return out_coords;
    
// };

// // bawah ni to connect the search Address function to debounce
// const debouncedSearch = debounce(searchAddress);

// // for DOM manipulation below
// input.addEventListener('input', (event) => {
//     debouncedSearch(event.target.value);
// });


// // input.addEventListener('change',()=>{
// //     const selectedValue= input.value;

// //     console.log(typeof selectedValue);
// //     let coordsData =null;

// //     // for(const coord of selectedValue){
// //     //     if()
// //     // }
// // })

// // console.log(searchAddress);





