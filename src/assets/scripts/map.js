/*** ********** ***/
/*** MAP STYLES ***/
/*** ********** ***/

const mapStyleLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
  maxZoom: 20
});

const satelliteStyleLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
  attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
});

/*** ************ ***/
/*** MAP CONTROLS ***/
/*** ************ ***/

// Zoom controls
document.querySelector('#map-zoom-minus').addEventListener('click', () => map.zoomOut());
document.querySelector('#map-zoom-plus').addEventListener('click', () => map.zoomIn());

// Geoloaction
document.querySelector('#map-geoloc').addEventListener('click', () => {
  navigator.geolocation.getCurrentPosition(
    async (pos) => {
      const {latitude, longitude} = pos.coords;
      map.setView([latitude, longitude], 12);
      addPinIcon(latitude, longitude);

      const query = new URLSearchParams({
        lat: latitude,
        lon: longitude
      });

      const res = await fetch(`https://api-adresse.data.gouv.fr/reverse?${query}`);
      const json = await res.json();

      const reversedSearch = json.features[0].properties.label;
      mapSearchInputEl.value = reversedSearch;

      document.querySelector('#lng').value = longitude;
      document.querySelector('#lat').value = latitude;

      params.set('lng', longitude);
      params.set('search', reversedSearch);
      params.set('lat', latitude);
      window.history.pushState("", "", `/?${params}`);
      window.location.reload();
    },
    (e) => {
      console.error(e);
      window.alert(e.message);
    });

});

// Map style switch
const mapStyleBtn = document.querySelector('#map-style-map');
const satelliteStyleBtn = document.querySelector('#map-style-satellite');

mapStyleBtn.addEventListener('click', () => {
  if (map.hasLayer(mapStyleLayer))
    return;

  map.addLayer(mapStyleLayer);
  map.removeLayer(satelliteStyleLayer);

  mapStyleBtn.classList.remove('bg-white', 'text-sky-500');
  mapStyleBtn.classList.add('bg-sky-500', 'text-white');

  satelliteStyleBtn.classList.add('bg-white', 'text-sky-500');
  satelliteStyleBtn.classList.remove('bg-sky-500', 'text-white');
});

satelliteStyleBtn.addEventListener('click', () => {
  if (map.hasLayer(satelliteStyleLayer))
    return;

  map.removeLayer(mapStyleLayer);
  map.addLayer(satelliteStyleLayer);

  mapStyleBtn.classList.add('bg-white', 'text-sky-500');
  mapStyleBtn.classList.remove('bg-sky-500', 'text-white');

  satelliteStyleBtn.classList.remove('bg-white', 'text-sky-500');
  satelliteStyleBtn.classList.add('bg-sky-500', 'text-white');
});

/*** ********* ***/
/*** MAP ICONS ***/
/*** ********* ***/
const circleIcon = L.icon({
  iconUrl: 'assets/map-circle-marker.svg',
  iconSize: [30, 30], // size of the icon
  // iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
});

const diamondIcon = L.icon({
  iconUrl: 'assets/map-diamond-marker.svg',
  iconSize: [20, 20], // size of the icon
});


function addCircleIcon(lat, lng) {
  L.marker([lat, lng], {icon: circleIcon}).addTo(map);
}


function addPinIcon(lat, lng) {
 const distance = parseInt(params.get('distance'))

  const circle = L.circle([lat, lng], {
    color: '#1766d9',
    fillColor: '#1766d9',
    fillOpacity: 0.1,
    radius: (distance > 0 ? distance : 20) * 1000,
    weight: 2,
  });

  circle.addTo(map);

  L.marker([lat, lng], {icon: diamondIcon}).addTo(map);

  map.fitBounds(circle.getBounds());
}


/* *** ****** *** */
/* *** SEARCH *** */
/* *** ****** *** */

const searchResultAutocompleteListEl = document.querySelector('#search-result-autocomplete-list');

const mapSearchInputEl = document.querySelector('#map-search');
mapSearchInputEl.addEventListener('input', async (e) => {
  const q = e.target.value;

  searchResultAutocompleteListEl.innerHTML = "";
  if (q.length < 3)
    return;

  const resultsEls = (await search(q)).map((data) => buildSearchResultElement(data));
  searchResultAutocompleteListEl.append(...resultsEls);
  showSearchResults();
});

mapSearchInputEl.addEventListener('blur', () => {
  setTimeout(hideSearchResults, 100);
});
mapSearchInputEl.addEventListener('focus', showSearchResults);


async function search(q) {
  const query = new URLSearchParams({
    q,
    autocomplete: 1,
  });

  const response = await fetch(`https://api-adresse.data.gouv.fr/search?${query}`);

  const json = await response.json();
  return json.features;
}


function hideSearchResults() {
  console.log('oui');
  searchResultAutocompleteListEl.classList.add('hidden');
}


function showSearchResults() {
  searchResultAutocompleteListEl.classList.remove('hidden');
}


function buildSearchResultElement(data) {
  const root = document.createElement('li');
  root.classList.add('search-result-item', 'border-b', 'last:border-b-0', 'border-slate-100');

  const link = document.createElement('a');
  link.classList.add(..."block p-4 bg-white md:hover:bg-slate-50 transition-colors".split(' '));
  link.innerHTML = data.properties.label;
  const urlQuery = new URLSearchParams({
    search: data.properties.label,
    lng: data.geometry.coordinates[0],
    lat: data.geometry.coordinates[1],
    distance: document.querySelector('#distance').value ?? undefined
  });
  link.href = `/?${urlQuery}`;

  root.appendChild(link);
  return root;
}


const map = L.map('map', {
  zoomControl: false,
  attributionControl: false,
});

const params = new URLSearchParams(document.location.search);
const paramLat = params.get('lat');
const paramLng = params.get('lng');

if (paramLng && paramLng.length > 0 && paramLat && paramLat.length > 0) {
  map.setView([paramLat, paramLng], 12);
  addPinIcon(paramLat, paramLng);
} else {
  map.setView([47.33880598769453, 2.3185135907715497], 6);
}

// Default map style
mapStyleLayer.addTo(map);

/**
 * @type {NodeListOf<HTMLElement>}
 */
const hotelCards = document.querySelectorAll(".hotel-card");
for (const card of hotelCards) {
  const {lat, lng} = card.dataset;
  addCircleIcon(parseFloat(lat), parseFloat(lng));
}