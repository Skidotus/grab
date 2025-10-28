const tempat = document.getElementById("coords");
//below for form input fill
//const borangPickup = document.getElementById("pickupInput")

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
    //minimum kena ada 3 huruf baru start cari
    if (query.length < 3) return;

    //baru buat API query utk send
    const url = `https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&limit=7`;
    

    //outside variable of lat and lon
    let out_lat = null;
    let out_lon = null;
    let out_coords = null;

    

    // and then for error handling dekat bawah
    try {
        // set URL response code from fetch method on the url string dekat atas
        const response = await fetch(url);
        //error handling
        if (!response.ok) throw new Error('Network error');
        // wait for payload to fully load
        const data = await response.json();
        // once payload dah load, baru retrieve the features; strt add, coords
        data.features.forEach(feature => {
            const properties = feature.properties;
            const geometry = feature.geometry;

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
            // option.dataset.lat = geometry.coordinates[0];
            // option.dataset.lon = geometry.coordinates[1];



            //below to put marker on the map
            pickupCoords = geometry.coordinates;
            out_coords = pickupCoords;


            

            // console.log(pickupCoords);

            // pickupCoords ? () => {
            //     new maplibregl.Marker({ color: '#260399' }) // Custom color
            //         // Set position using the markerCoords variable
            //         .setLngLat(pickupCoords)
            //         // Add a simple popup
            //         .setPopup(new maplibregl.Popup({ offset: 25 })
            //             .setHTML('<h3>Pickup</h3>'))
            //         // Add the marker to the map
            //         .addTo(map);
            // } : alert("Pickup not detected");





            // console.log(option.dataset.lat,option.dataset.lon);
            datalist.appendChild(option);
        });



    } catch (error) {
        console.error('Error fetching data: ', error)
    }

    return out_coords;
    
};

// bawah ni to connect the search Address function to debounce
const debouncedSearch = debounce(searchAddress);

// for DOM manipulation below
input.addEventListener('input', (event) => {
    debouncedSearch(event.target.value);
});


// input.addEventListener('change',()=>{
//     const selectedValue= input.value;

//     console.log(typeof selectedValue);
//     let coordsData =null;

//     // for(const coord of selectedValue){
//     //     if()
//     // }
// })

// console.log(searchAddress);





