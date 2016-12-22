var apiUrl = "http://localhost:8000/api/v1/";


function loadCountOfReport(long, lat) {
     
    var url = apiUrl + "insertmarker";
    data = {};
    data.lat=lat;
    data.lon=long;
    data=JSON.stringify(data);
    var options = {
        url : url,
        method: "POST",
        headers: { 'Content-Type': 'application/json' },
        data : data
    };
    $.ajax(options).done(
        function(response) {
           // alert(response);
        }
    );
}
function loadAllMarkers(callback) {
    markers  = [];
    var url = apiUrl + "all_markers";
    var options = {
        url : url,
        method: "GET",
        headers: { 'Content-Type': 'application/json' },
    };
    $.ajax(options).done(
        function(response) {
            callback(response);        
        }
    );
}

function DistanceAllMarkers(source, des,distance) {
     
    var url = apiUrl + "all_Distances";
    data = {};
    data.source_address=source;
    data.des_address=des;
    data.Distance=distance;
    data=JSON.stringify(data);
    var options = {
        url : url,
        method: "POST",
        headers: { 'Content-Type': 'application/json' },
        data : data
    };
    $.ajax(options).done(
        function(response) {
            //alert(response);
        }
    );
}
function distanceTwoMarkers(source, des,distance) {
     
    var url = apiUrl + "all_Distances";
    data = {};
    data.source_address=source;
    data.des_address=des;
    data.Distance=distance;
    data=JSON.stringify(data);
    var options = {
        url : url,
        method: "POST",
        headers: { 'Content-Type': 'application/json' },
        data : data
    };
    $.ajax(options).done(
        function(response) {
            alert(response);
        }
    );
}
function Dijkstra(matrixWidth,source_address,des_address,array) {
     
    var url = apiUrl + "dijkstra";
    var value;
    data = {};
    data.matrixWidth=matrixWidth;
    data.source_address=source_address;
    data.des_address=des_address;
    data.array=array;
    data=JSON.stringify(data);
    var options = {
        url : url,
        method: "POST",
        headers: { 'Content-Type': 'application/json' },
        data : data,
        async: false
    };
    $.ajax(options).done(
        function(response) {
            value=response;
        }
    );
    return value;

}
function CountMarkers() {
     
    var url = apiUrl + "count_markers";
    var result;
    var options = {
        url : url,
        method: "GET",
        headers: { 'Content-Type': 'application/json' },
        async: false
    };
    $.ajax(options).done(
        function(response) {
           result = response;
        }
    );
    return result;
}
function Dijkstra2(source,des,graph) {
     
    var url = apiUrl + "dijkstra2";
    var value;
    data = {};
    data.source=source;
    data.des=des;
    data.graph=graph;
    data=JSON.stringify(data);
    var options = {
        url : url,
        method: "POST",
        headers: { 'Content-Type': 'application/json' },
        data : data,
        async: false
    };
    $.ajax(options).done(
        function(response) {
            value=response;
        }
    );
    return value;

}
function GetLatOneMarker(id_marker,callback) {
     
    var url = apiUrl + "get_lat_one_marker";
    data = {};
    var value;
    data.id=id_marker;
    data=JSON.stringify(data);
    var options = {
        url : url,
        method: "POST",
        headers: { 'Content-Type': 'application/json' },
        data : data
    };
    $.ajax(options).done(
        function(response) {
            callback(id_marker, response);
        }
    );
}
function GetLngOneMarker(id_marker, source, callback) {
     
    var url = apiUrl + "get_lng_one_marker";
    data = {};
    var value;
    data.id=id_marker;
    data=JSON.stringify(data);
    var options = {
        url : url,
        method: "POST",
        headers: { 'Content-Type': 'application/json' },
        data : data
    };
    $.ajax(options).done(
        function(response) {
            callback(id_marker, source, response);
        }
    );
}
function insertDistance(marker1, marker2) {
     
    var url = apiUrl + "addDistance";
    data = {};
    data.marker1=marker1;
    data.marker2=marker2;
    data=JSON.stringify(data);
    var options = {
        url : url,
        method: "POST",
        headers: { 'Content-Type': 'application/json' },
        data : data
    };
    $.ajax(options).done(
        function(response) {
            alert(response);
        }
    );
}
function getCorOfMarkers(markers) {
     console.log(markers)
    var url = apiUrl + "DrawStress";
    var value;
    data = {};
    data.markers=markers;
    data=JSON.stringify(data);
    var options = {
        url : url,
        method: "POST",
        headers: { 'Content-Type': 'application/json' },
        data : data,
        async: false
    };
    $.ajax(options).done(
        function(response) {
            console.log(response)
            value=response;
        }
    );
    return value;
}
function findShortPath(source,des,mode) {
    
    var url = apiUrl + "Dijkstra3";
    var value;
    data = {};
    data.source=source;
    data.des=des;
    data.mode = mode;
    data=JSON.stringify(data);
    var options = {
        url : url,
        method: "POST",
        headers: { 'Content-Type': 'application/json' },
        data : data,
        async: false
    };
    $.ajax(options).done(
        function(response) {
            value=response;
        }
    );
    return value;
}

