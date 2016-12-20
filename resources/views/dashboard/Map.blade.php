<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Đồ án 3</title>
    <link rel="stylesheet" href="{{ asset('css/map.css') }}">
</head>
<body>
    <div id="floating-panel">
        <div id="temp"></div>
        <div class="form-group has-feedback">
            <input  id="address_Source" type="email" class="form-control" placeholder="Source address">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input id="address_Des" type="email" class="form-control" placeholder="Des address">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <!--<button onclick="addMultiMarkers(map);" type=button value="Add Markers" class="btn btn-primary">Add Markers</button>-->
        <button onclick="DrawAllMarkers(map);" type=button value="View All" class="btn btn-success">View All</button>
        <!--<button onclick="clickDistance(marker);" type=button value="add 1 Distance" class="btn btn-info">add 1 Distance</button>-->
        <button onclick="distanceTwoMarkes();" type=button value="DISTANCE" class="btn btn-warning">DISTANCE</button>
        <button onclick="DrawPolylineTwoMarkers(map);" type=button value="Draw street" class="btn btn-danger">Draw street</button>
    </div>
    <div id="map"></div>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="{{ asset('js/map.js') }}"></script>

<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDUhgBbMvryjcfydyz78cETeIazfLgjFsY&callback=initMap">
</script>
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/call-api.js') }}"></script>
</body>
</html>