/**
 * Created by Jonnt Nguyen on 9/25/2016.
 */

function initMap() {
    var directionsService = new google.maps.DirectionsService;
    var directionsDisplay = new google.maps.DirectionsRenderer;
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13,
        center: {lat: 20.9970736, lng: 105.8375195}
    });
    directionsDisplay.setMap(map);
    //setMarkers(map);//ve nhieu marker.
    var onChangeHandler = function() {
        calculateAndDisplayRoute(directionsService, directionsDisplay);
    };
    var geocoder = new google.maps.Geocoder();

    var onchangeaddress=function () {
        var distance = getDistancebyaddress(geocoder,document.getElementById('start').value,document.getElementById('end').value);
        document.getElementById('distance').value=distance;
    };
    var marker = new google.maps.Marker();
    document.getElementById('start').addEventListener('change', onChangeHandler);
    document.getElementById('end').addEventListener('change', onChangeHandler);
    document.getElementById('mode').addEventListener('change',onChangeHandler);
    document.getElementById('start').addEventListener('change', onchangeaddress);
    document.getElementById('end').addEventListener('change', onchangeaddress);
    calculateGetDistance();
    //var button = document.getElementById('b');
    //button.addEventListener('click', exportToCsv());

}
var markers = [
    ['marker1', 20.9870736,  105.8375195, 1],
    ['marker2', 20.995976, 105.850063, 2],
    ['marker3', 20.9650736,  105.8365195, 3],
    ['marker4', 20.9949736,  105.8385195, 4],
    ['marker5', 21.018937, 105.762692, 5],
    ['marker6', 20.95449736,  105.8385195, 6],
    ['marker7', 21.003249, 105.820251,7],
    ['marker8', 20.991109, 105.820980,8],
    ['marker9', 21.009799,105.8371595, 9],
    ['marker10', 21.0084369,105.8282331,10],
    ['marker11', 20.959454, 105.871832, 11],
    ['marker12', 21.0080883,105.8308718, 12],
    ['marker13',20.9911674,105.8602184,13],
    ['marker14', 20.9797109,105.7270573, 14],
    ['marker15', 20.983678, 105.772290, 15],
    ['marker16', 21.004315,105.8165785,16],
    ['marker17', 21.0100794,105.8103142, 17],
    ['marker18', 20.988443, 105.896926, 18],
    ['marker19', 20.9860827,105.8022277, 19],
    ['marker20', 21.0100794,105.8103142, 20],
    ['marker21', 20.9629574,105.7873589, 21],
    ['marker22', 20.991215,105.8007253, 22],
    ['marker23', 21.006513, 105.761835, 23],
    ['marker24', 20.9644332,105.8417782, 24],
    ['marker25', 21.025354, 105.793498, 25]

];
function setMarkers(map) {

    var image = {
        url: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
        size: new google.maps.Size(40, 32),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(0, 32)
    };
    var shape = {
        coords: [1, 1, 1, 20, 18, 20, 18, 1],
        type: 'poly'
    };
    for (var i = 0; i < markers.length; i++) {
        var beach = markers[i];
        var marker = new google.maps.Marker({
            position: {lat: beach[1], lng: beach[2]},
            map: map,
            icon: image,
            shape: shape,
            title: beach[0],
            zIndex: beach[3]
        });
    }
}
var rad = function(x) {
    return x*Math.PI/180;
};
var d;
var distance =new Array([2],[markers.length]);
var data="noi dung duoi day";

function getDistance(p1,p2) {

    var R = 6378137; // Earth’s mean radius in meter
    var dLat = rad(p2.lat - p1.lat);
    var dLong = rad(p2.lng- p1.lng);
    var a = Math.sin(dLat/2)*Math.sin(dLat/2)+Math.cos(rad(p1.lat))*Math.cos(rad(p2.lat))*Math.sin(dLong/2)*Math.sin(dLong/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var d = R * c;
    return d; // returns the distance in meter
}

function getDistancebymarker(marker1,marker2) {

    var R = 6378137; // Earth’s mean radius in meter
    var dLat = rad(marker2[1] - marker1[1]);
    var dLong = rad(marker2[2]- marker1[2]);
    var a = Math.sin(dLat/2)*Math.sin(dLat/2)+Math.cos(rad(marker1[1]))*Math.cos(rad(marker2[1]))*Math.sin(dLong/2)*Math.sin(dLong/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var d = R * c;
    return d; // returns the distance in meter
}

function getDistancebyaddress(geocoder,address1,address2) {

    var lat1;
    var lat2;
    var lng1;
    var lng2;

    var latLng1Request = new Promise(function(resolve, reject) {
        geocoder.geocode({'address': address1}, function(results, status) {
            if (status === 'OK') {
                lat1=(results[0].geometry.location).lat();
                lng1=(results[0].geometry.location).lng();
                resolve();
            } else {
                alert('Geocode was not successful for the following reason: '+status);
                resolve();
            }
        });
    });

    var latLng2Request = new Promise(function(resolve, reject) {
        geocoder.geocode({'address': address2}, function(results, status) {
            if (status === 'OK') {
                lat2=(results[0].geometry.location).lat();
                lng2=(results[0].geometry.location).lng();
                resolve();
            } else {
                alert('Geocode was not successful for the following reason: ' + status);
                resolve();
            }
        });
    });

    Promise.all([latLng1Request, latLng2Request]).then(function() {
        var R = 6378137; // Earth’s mean radius in meter
        var dLat = rad(lat2 - lat1);
        var dLong = rad(lng2- lng1);
        var a = Math.sin(dLat/2)*Math.sin(dLat/2)+Math.cos(rad(lat1))*Math.cos(rad(lat2))*Math.sin(dLong/2)*Math.sin(dLong/2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        d = R * c;
        // returns the distance in meter
    });
    return d;
}

function geocodeAddress(geocoder,pn) {

    var address = "4 xa dan ,vietnam";
    geocoder.geocode({'address': address}, function(results, status) {
        if (status === 'OK') {
        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });
}

function calculateAndDisplayRoute(directionsService, directionsDisplay) {

    directionsService.route({
        origin: document.getElementById('start').value,
        destination: document.getElementById('end').value,
        travelMode: document.getElementById('mode').value
    }, function(response, status) {
        if (status === 'OK') {
            directionsDisplay.setDirections(response);
        } else {
            window.alert('Directions request failed due to ' + status);
        }
    });
}

function calculateGetDistance(){
    for (var i = 0; i < (markers.length-23); i++)
        for (var j = 0; j< (markers.length-24); j++)
        {
            distance[i][j]= getDistancebymarker(markers[i],markers[j]);
            data.concat(toString(distance));
            exportToCsv(data);
        }
}

function changeColorMarker() {
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13,
        center: {lat: 20.9970736, lng: 105.8375195}
    });
    var image = {
        url: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
        size: new google.maps.Size(40, 32),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(0, 32)
    };
    var shape = {
        coords: [1, 1, 1, 20, 18, 20, 18, 1],
        type: 'poly'
    };
    for (var i = 0; i < markers.length; i++) {
        var beach = markers[i];
        var marker = new google.maps.Marker({
            position: {lat: beach[1], lng: beach[2]},
            map: map,
            icon: image,
            shape: shape,
            title: beach[0],
            zIndex: beach[3]
        });
    }

}

function Distanceall(){
    var p2 = new Promise(function(resolve, reject) {
        calculateGetDistance();
        resolve();
    });
    p2.then(function() {
        alert(distance[1][2]);
    });

}

function Runapp() {
    var latLng1Request1 = new Promise(function(resolve, reject) {
        calculateGetDistance();
        resolve();
    });

    var latLng2Request1 = new Promise(function(resolve, reject) {
        alert(2222);
        resolve();
    });

    Promise.all([latLng1Request1, latLng2Request1]).then(function() {
      alert(333333)
    });
    return 2;
}
function exportToCsv(Co_string) {
    var myCsv = "Col1,Col2,Col3\nval1,val2,val3".concat(Co_string);

    window.open('data:text/csv;charset=utf-8,' + escape(myCsv));
}