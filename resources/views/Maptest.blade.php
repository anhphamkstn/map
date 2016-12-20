<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Directions service</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        #map {
            height: 100%;
        }
        #floating-panel {
            position: absolute;
            top: 10px;
            left: 25%;
            z-index: 5;
            background-color: #fff;
            padding: 5px;
            border: 1px solid #999;
            text-align: center;
            font-family: 'Roboto','sans-serif';
            line-height: 30px;
            padding-left: 10px;
        }

        #floating-panel1 {
            position: absolute;
            top: -1px;
            left: 70%;
            z-index: 5;
            background-color: #fff;
            padding: 5px;
            border: 1px solid #999;
            text-align: center;
            font-family: 'Roboto','sans-serif';
            line-height: 30px;
            padding-left: 10px;
        }
    </style>
    <script type="text/javascript" src="js/jquery-1.7.min.js"></script>
</head>
<body>
<div id="floating-panel">
    <b>Start: </b>
    <select id="start">
        <option value="cong vien thong nhat,vietnam">Cong vien thong nhat</option>
        <option value="1 giai phong,vietnam">bach khoa</option>
        <option value="cong vien thu le,vietnam">cong vien thu le </option>
        <option value="cong vien bach thao,vietnam">cong vien bach thao</option>
        <option value="Cong Vien Yen So,vietnam">Cong vien Yen So</option>
        <option value="Hoc Vien An Ninh Nhan Dan,vietnam">Hoc Vien An Ninh Nhan Dan,vietnam</option>
        <option value="San bay Noi Bai,vietnam">San bay Noi Bai,vietnam</option>
        <option value="Ben xe gia lam,vietnam">Ben xe gia lam,vietnam</option>
        <option value="Ben xe giap bat,vietnam">Ben xe giap bat,vietnam</option>
        <option value="Ben xe My Dinh,vietnam">Ben xe My Dinh,vietnam</option>
        <option value="Ben xe Luong Yen,vietnam">Ben xe Luong Yen,vietnam</option>
        <option value="Cau Nhat Tan,vietnam">Cau Nhat Tan,vietnam</option>
    </select>
    <b>End: </b>
    <select id="end">

        <option value="cong vien thong nhat,vietnam">Cong vien thong nhat</option>
        <option value="1 giai phong,vietnam">bach khoa</option>
        <option value="cong vien thu le,vietnam">cong vien thu le </option>
        <option value="cong vien bach thao,vietnam">cong vien bach thao</option>
        <option value="Cong Vien Yen So,vietnam">Cong vien Yen So</option>
        <option value="Hoc Vien An Ninh Nhan Dan,vietnam">Hoc Vien An Ninh Nhan Dan,vietnam</option>
        <option value="San bay Noi Bai,vietnam">San bay Noi Bai,vietnam</option>
        <option value="Ben xe gia lam,vietnam">Ben xe gia lam,vietnam</option>
        <option value="Ben xe giap bat,vietnam">Ben xe giap bat,vietnam</option>
        <option value="Ben xe My Dinh,vietnam">Ben xe My Dinh,vietnam</option>
        <option value="Ben xe Luong Yen,vietnam">Ben xe Luong Yen,vietnam</option>
        <option value="Cau Nhat Tan,vietnam">Cau Nhat Tan,vietnam</option>
    </select>
    <select id="mode">
        <option value="DRIVING">Driving</option>
        <option value="WALKING">Walking</option>
        <option value="BICYCLING">Bicycling</option>
        <option value="TRANSIT">Transit</option>
    </select>

    <div class="info-box">
        <button onclick="changeColorMarker()">Change</button>
        <button id="b">export to CSV</button>
        <span class="info-box-text">Distance:</span>
        <input id="distance" type="text" name="firstname" value="Auto...">
    </div>
</div>
<div id="map"></div>
<script >

</script>

<script type="text/javascript" src="source.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC72IeTR-t8-bOq7-jSEGmWqB5ODB_aR_8&callback=initMap"> </script>
</body>
</html>