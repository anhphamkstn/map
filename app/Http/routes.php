<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');

Route::get('/', function () {
    return view('dashboard.Map');
});

Route::group(['prefix' => 'api/v1'], function () {
    Route::post('login', 'Api\UserController@postLogin');

    Route::post('store', 'Api\UserController@store');
    Route::post('refresh', 'Api\AuthController@refreshToken');
    Route::post('Distance', 'Api\DistanceController@distance');
    Route::post('insertmarker', 'Api\getDataController@insertMarker');
    Route::get('marker', 'Api\getDataController@showMarker');
    Route::get('all_markers', 'Api\getDataController@allMarkers');
    Route::post('all_Distances', 'Api\getDataController@DistanceAllMarkers');
    Route::post('dijkstra', 'Api\getDataController@Dijkstra');
    Route::get('count_markers', 'Api\getDataController@CountMarkers');
    Route::get('All_polyline_markers', 'Api\getDataController@PolylineMarkers');
    Route::post('dijkstra2', 'Api\getDataController@Dijkstra2');
    Route::get('All_polyline_markers2', 'Api\getDataController@PolylineMarkers2');
    Route::post('get_lat_one_marker', 'Api\getDataController@getLat');
    Route::post('get_lng_one_marker', 'Api\getDataController@getLng');
    Route::post('DrawStress', 'Api\getDataController@DrawStress');
    Route::post('addDistance', 'Api\getDataController@insertDistance');
    Route::get('graph', 'Api\getDataController@graph');
    Route::post('Dijkstra3', 'Api\getDataController@Dijkstra3');
    Route::post('Dijkstra2', 'Api\getDataController@Dijkstra2');
    Route::post('dijkstra4', 'Api\getDataController@Dijkstra4');
    Route::post('DFS', 'Api\getDataController@DFS');

    });
