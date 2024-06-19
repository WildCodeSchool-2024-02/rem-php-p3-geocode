import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';

import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/_map.scss';
import './styles/_userProfile.scss';
import './styles/_footer-navbar.scss';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

function initMap()
{
    var directionsService = new google.maps.DirectionsService();
    var directionsRenderer = new google.maps.DirectionsRenderer();
    const myLatLng = { lat: 50.63102272917646, lng: 3.054790378008385 };
    const otherLatLng = { lat: 50.6193174, lng: 3.1314002 };
    const marker3 = { lat: 50.6026031, lng: 3.0697877};
    const marker4 = {coords:{ lat: 50.6915893, lng: 3.1741734}};

    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 12,
        center: myLatLng,
    });
    directionsRenderer.setMap(map);

    google.maps.event.addListener(map, 'click', (event) => {
        addMarker({coords:event.latLng});
    })

    let mark1 = new google.maps.Marker({
        position: myLatLng,
        map,
        title: "Hello World!",
    });

    let mark2 = new google.maps.Marker({
        position: otherLatLng,
        map,
        title: "Test 2!",
    });

    let infoWindow = new google.maps.InfoWindow({
        content: '<h3>Test infomap</h3>' +
          '<button>Delete me !</button>'
    });

    mark1.addListener("click", function () {
        infoWindow.open(map, mark1);
    });

    function addMarker(property)
    {
        let marker = new google.maps.Marker({
            position: property.coords,
            map: map,
            title: "Hello World!",
        });
        marker.addListener('click', function () {
            infoWindow.open(map, marker);
        })
    }

    function deleteMarker()
    {

    }

    addMarker({coords:{ lat: 50.6026031, lng: 3.0697877}});
    addMarker(marker4);

    calcRoute(directionsService, directionsRenderer);
}

function calcRoute(directionsService, directionsRenderer)
{

    var start = new google.maps.LatLng(50.63102272917646, 3.054790378008385);
    var end = new google.maps.LatLng(50.6193174, 3.1314002);

    var request = {
        origin : start,
        destination : end,
        travelMode : 'DRIVING'
    };
    directionsService.route(request, function (response, status) {
        if (status === 'OK') {
            directionsRenderer.setDirections(response);
        }
    });
    console.log(request);
}

window.initMap = initMap;
