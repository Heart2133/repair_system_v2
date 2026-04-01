// leaflet-map
var mymap = L.map('leaflet-map').setView([51.505, -0.09], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: '&copy; OpenStreetMap'
}).addTo(mymap);


// leaflet-map-marker
var markermap = L.map('leaflet-map-marker').setView([51.505, -0.09], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: '&copy; OpenStreetMap'
}).addTo(markermap);

L.marker([51.5, -0.09]).addTo(markermap);

L.circle([51.508, -0.11], {
    color: '#34c38f',
    fillColor: '#34c38f',
    fillOpacity: 0.5,
    radius: 500
}).addTo(markermap);

L.polygon([
    [51.509, -0.08],
    [51.503, -0.06],
    [51.51, -0.047]
], {
    color: '#556ee6',
    fillColor: '#556ee6',
}).addTo(markermap);


// popup
var popupmap = L.map('leaflet-map-popup').setView([51.505, -0.09], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: '&copy; OpenStreetMap'
}).addTo(popupmap);

L.marker([51.5, -0.09]).addTo(popupmap)
    .bindPopup("<b>Hello world!</b><br />I am a popup.").openPopup();


// custom icons
var customiconsmap = L.map('leaflet-map-custom-icons').setView([51.5, -0.09], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap'
}).addTo(customiconsmap);

var LeafIcon = L.Icon.extend({
    options: {
        iconSize: [45, 95],
        iconAnchor: [22, 94],
        popupAnchor: [-3, -76]
    }
});

var greenIcon = new LeafIcon({ iconUrl: 'assets/images/logo.svg' });

L.marker([51.5, -0.09], { icon: greenIcon }).addTo(customiconsmap);


// interactive map
var interactivemap = L.map('leaflet-map-interactive-map').setView([37.8, -96], 4);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: '&copy; OpenStreetMap'
}).addTo(interactivemap);


// group control
var cities = L.layerGroup();

L.marker([39.61, -105.02]).addTo(cities);
L.marker([39.74, -104.99]).addTo(cities);

var grayscale = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
var streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');

var layergroupcontrolmap = L.map('leaflet-map-group-control', {
    center: [39.73, -104.99],
    zoom: 10,
    layers: [streets, cities]
});

L.control.layers({
    "Default": streets
}, {
    "Cities": cities
}).addTo(layergroupcontrolmap);