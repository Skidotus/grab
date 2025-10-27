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


        // script for search suggestions
        // Below for delay search typing
        function debounce(func, delay = 300) {
            let timer;
            //below to allow multiple argumes(by typing of course its not going to be finite)
            return (...args)=> {
                //below untuk if everytime dia type, time reset.
                clearTimeout(timer);
                timer = setTimeout(() => func.apply(this, args), delay);
            };


        }

        // get the html id of search input and also unordered list
        const input = document.getElementById('address-input');
        const container = document.getElementById('suggestions-container');

        // lepastu baru fetch the API and by using the function debounce to request it
        // set async to let function call and get all the file first then baru run
        const searchAddress = async (query) => {
            //clearkan dlu previous result
            container.innerHTML = '';
            //minimum kena ada 3 query utk return
            if (query.length < 3) return;

            //baru buat API query utk send
            const url = `https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&limit=7`;

            // and then for error handling dekat bawah
            try {
                // set URL response code from fetch method on the url string dekat atas
                const response = await fetch(url);
                // wait for payload to fully load
                const data = await response.json();

                // once payload dah load, baru retrieve the features; strt add, coords
                data.features.forEach(feature => {
                    const properties = feature.properties;

                    // address pulak bawah ni
                    const address = [
                        properties.name,
                        properties.street,
                        properties.city,
                        properties.state,
                        properties.country
                    ].filter(Boolean).join(', ');

                    // then baru buat list 
                    // original
                    // const suggestionItem = document.createElement('div');
                    // suggestionItem.textContent = address;
                    // suggestionItem.style.padding = '8px';
                    // suggestionItem.style.cursor = 'pointer';
                    // suggestionItem.style.borderBottom = '1px solid #eee';

                    // this if we use datalist instead of container
                    const suggestionItem = document.createElement('option');
                    suggestionItem.value = address;
                    //if the user click on one of the suggestion then fill it
                    suggestionItem.onclick = () => {
                        input.value = address;
                        container.innerHTML = '';
                        console.log('Selected Coordinates:', properties.lat, properties.lon);
                    };
                    // baru append next suggenstion kalau ada
                    container.appendChild(suggestionItem);
                });

            } catch (error) {
                console.error('Error fetching data: ', error)
            }
        };

        // bawah ni to connect the search Address function to debounce
        const debouncedSearch =  debounce(searchAddress);

        // for DOM manipulation below
        input.addEventListener('input',(event)=>{
            debouncedSearch(event.target.value);
        });