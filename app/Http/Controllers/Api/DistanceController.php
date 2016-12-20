<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Helpers\Response;
use App\Helpers\UserHelper;
use App\Http\Requests;
use Mockery\CountValidator\Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Input;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\FactoryService;
use Illuminate\Support\Facades\DB;
use App\Role;


class DistanceController extends ApiController
{
    public function __construct()
    {
        $this->service = FactoryService::getUserService();
    }

    public function distance(Request $request)
    {

        $lat_b=$request['lat2'];
        $lon_b=$request['lon2'];
        $lat_a=$request['lat1'];
        $lon_a=$request['lon1'];
        $delta_lat =$lat_b - $lat_a ;
        $delta_lon = $lon_b - $lon_a ;

        $earth_radius = 6372.795477598;

        $alpha    = $delta_lat/2;
        $beta     = $delta_lon/2;
        $a        = sin(deg2rad($alpha)) * sin(deg2rad($alpha)) + cos(deg2rad($lat_a)) * cos(deg2rad($lat_b)) * sin(deg2rad($beta)) * sin(deg2rad($beta)) ;
        $c        = asin(min(1, sqrt($a)));
        $distance = 2*$earth_radius * $c;
        $distance = round($distance, 4);

        return $distance;

    }
}
