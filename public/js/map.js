var map;
var acction_add_markers = 0;
var count = 0;
var source;
var key_click = 0
var key_click_distance = 0;
var Des;
var multy_markers;
var flightPlanCoordinates = [];
var rad = function (x) {
    return x * Math.PI / 180;
};
function initMap() {
    var directionsService = new google.maps.DirectionsService;
    var directionsDisplay = new google.maps.DirectionsRenderer;
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13,
        center: { lat: 20.9970736, lng: 105.8375195 }
    });
}

function addEventClick(map) {
    console.log(acction_add_markers);
    if (actionKey(acction_add_markers)) {
        alert('OK! you can add markers now!');
        google.maps.event.addListener(map, 'click', function (e) {
            loadCountOfReport(e.latLng.lng(), e.latLng.lat());
        });
        drawmarker(map);
        console.log(acction_add_markers);
    }
    else
        return;
}

var info_marker1 = 0;
var info_marker2 = 0;
function clickDistance(marker) {
    if (1) {
        //alert('OK! you can add street now!');
        console.log("add one distance");
        marker.addListener('click', function (e) {
            var lat = e.latLng.lat();
            var lng = e.latLng.lng();
            var index = marker.zIndex;
            var cars = 0;
            key_click += 1;
            if (key_click == 1) {
                info_marker1 = [index, lat, lng];
                alert(info_marker1);
            }
            if (key_click % 2 == 0) {
                //gui request.
                lat = e.latLng.lat();
                lng = e.latLng.lng();
                index = marker.zIndex;

                info_marker2 = [index, lat, lng];
                //alert(info_marker2);
                insertDistance(info_marker1, info_marker2);

                key_click = 0;
            }
            else {
                //  alert(marker.zIndex);
            }
        });

    }
    else
        return;
    key_click_distance = 0;
}
function actionKey($key) {
    if ($key == 0) return 1;
    else return 0;
}
function drawmarker(map) {
    map.addListener('click', function (event) {
        addMarker(event.latLng);
    });

}
function addMarker(location) {
    var marker = new google.maps.Marker({
        position: location,
        map: map
    });
    markers.push(marker);
}
function DrawAllMarkers(map) {
    var p2 = new Promise(function (resolve, reject) {
        loadAllMarkers();
        resolve();
    });
    p2.then(function () {
        var image = {
            url: 'picture/bus3.png',
            //url:'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
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
                position: { lat: beach[1], lng: beach[2] },
                map: map,
                icon: image,
                shape: shape,
                title: beach[0],
                zIndex: beach[3]
            });
            clickDistance(marker);
        }
    });


}
function addMultiMarkers(map) {
    addEventClick(map)
}
function calculateAndDisplayRoute(directionsService, directionsDisplay) {
    directionsService.route({
        origin: document.getElementById('SourceAddress').value,
        destination: document.getElementById('DesAddress').value,
        travelMode: "WALKING"
    }, function (response, status) {
        if (status === 'OK') {
            directionsDisplay.setDirections(response);
        } else {
            window.alert('Directions request failed due to ' + status);
        }
    });
}
function Run() {
    RunUpdatedistance();
    Run_Dijkstra2();
}
function DrawPolylineTwoMarkers(map) {
    var source = Number(document.getElementById('address_Source').value);
    var des = Number(document.getElementById('address_Des').value);
    var value_marker = PathMarker(source, des);
    console.log(value_marker);
    var flightPlanCoordinates = DrawStreet(value_marker);
    console.log(flightPlanCoordinates);
    //-------------------
    var lineSymbol = {
        path: google.maps.SymbolPath.CIRCLE,
        scale: 8,
        strokeColor: '#393'
    };
    var line = new google.maps.Polyline({
        path: flightPlanCoordinates,
        icons: [{
            icon: lineSymbol,
            offset: '100%'
        }],
        map: map
    });

    animateCircle(line);
    //--------

    var flightPath = new google.maps.Polyline({
        path: flightPlanCoordinates,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2,
        travelMode: 'Driving'
    });
    flightPath.setMap(map);
}
function animateCircle(line) {
    var count = 0;
    window.setInterval(function () {
        count = (count + 1) % 200;

        var icons = line.get('icons');
        icons[0].offset = (count / 2) + '%';
        line.set('icons', icons);
    }, 20);
}
function GetSourceAndDes() {
    google.maps.event.addListener(map, 'click', function (e) {
        source = e.latLng.lng();
        Des = e.latLng.lat();
        var so = { lat: Des, lng: source };
        console.log(so);
        flightPlanCoordinates.push(so);
        console.log(flightPlanCoordinates);
        //loadCountOfReport(e.latLng.lng(),e.latLng.lat());
    });

    alert(source);
}
function getDistancebymarker(marker1, marker2) {

    var R = 6378137; // Earth’s mean radius in meter
    var dLat = rad(marker2['lat'] - marker1['lat']);
    var dLong = rad(marker2['lng'] - marker1['lng']);
    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(rad(marker1['lat'])) * Math.cos(rad(marker2['lat'])) * Math.sin(dLong / 2) * Math.sin(dLong / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var d = R * c;
    return d; // returns the distance in meter
}
function RunUpdatedistance() {
    for (var i = 0; i < flightPlanCoordinates.length; i++)
        for (var j = 1; j < flightPlanCoordinates.length; j++) {
            var distance_local = getDistancebymarker(flightPlanCoordinates[i], flightPlanCoordinates[j]);
            //DistanceAllMarkers(i,j,distance_local);
            console.log(DistanceAllMarkers(i, j, distance_local));
        }
}
function GetPolylineDijkstra($temp) {

    for (var i = 0; i < $temp.length; i++) {

    }
}
function Run_Dijkstra() {
    var source = Number(document.getElementById('address_Source').value);
    var des = Number(document.getElementById('address_Des').value);
    var matrixWidth = Number(CountMarkers());
    var array = "0,1,4:0,1,4:0,1,4:0,1,4:0,1,4:0,1,34:0,1,54:0,2,I:1,2,5:1,3,5:2,3,5:3,4,5:4,5,5:4,5,5:2,10,30:2,11,40:5,19,20:10,11,20:12,13,20";
    console.log(Dijkstra(matrixWidth, source, des, array));
}
function Run_Dijkstra2() {
    var source = Number(document.getElementById('address_Source').value);
    var des = Number(document.getElementById('address_Des').value);
    // var matrixWidth=Number(CountMarkers());
    var array = { "1": { "2": 3, "4": 3, "6": 6 }, "2": { "1": 3, "4": 1, "5": 3 }, "3": { "5": 2, "6": 3 }, "4": { "1": 3, "2": 1, "5": 1, "6": 2 }, "5": { "2": 3, "3": 2, "4": 1, "6": 5 }, "6": { "1": 6, "3": 3, "4": 2, "5": 5 } };
    multy_markers = Dijkstra2(source, des, array).split('-');
    console.log(multy_markers);
}
function add_markers_to_polyline() {
    console.log(multy_markers);
    for (var i = 1; i < multy_markers.length; i++) {
        var value = Number(multy_markers[i]) + 92;
        GetLatOneMarker(value, function (value, result) {
            var source = Number(result);
            GetLngOneMarker(value, source, function (value, source, result) {
                var destination = Number(result);
                var so = { lat: source, lng: destination };
                flightPlanCoordinates.push(so)
                console.log(flightPlanCoordinates);
            });
        });
    }
}
function getDistanceInDB(map) {
    count += 1;
    //gan gia tri cho 1 doan thang.
    if (count == 2) count = 0;
    console.log(count);
    google.maps.event.addListener(marker, 'click', function () {
        marker.setIcon("../../../picture/marker04.png");
    });
}
function distanceTwoMarkes() {
    var src_ = 1;
    var des_ = 2;
    var distance_ = 0;
    var marker1 = {
        "lat": 20.980834785155,
        "lng": 105.89223861694
    };
    var marker2 = {
        "lat": 20.978590844524,
        "lng": 105.81739425659
    };
    distance_ = getDistancebymarker(marker1, marker2);

    distanceTwoMarkers(src_, des_, distance_);
}
function addDistance(marker) {
    key_click_distance += 1;
    console.log(key_click_distance);
}