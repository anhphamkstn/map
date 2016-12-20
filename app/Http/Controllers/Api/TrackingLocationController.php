<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\FactoryService;
use App\Helpers\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Input;
use App\Helpers\GeocodingHelper;

class TrackingLocationController extends ApiController
{
    public function __construct()
    {
        $this->service = FactoryService::getTrackingLocationService();
    }

    public function index() {
        $filter = Input::get();

        if (!isset($filter['address']))
            return Response::responseMissingParameter();

        $address = $filter['address'];
        $addressBounds = GeocodingHelper::getAddressBoundsFromGoogleGeocodingApi($address);

        $trackingRecords = $this->service->getSalesmansFromBounds($addressBounds);

        $salesmanIds = [];
        $transformedTrackingRecords = [];

        foreach ($trackingRecords as $trackingRecord)
        {
            if (in_array($trackingRecord['user_id'], $salesmanIds))
                continue;

            $salesmanIds[] = $trackingRecord['user_id'];

            $transformedTrackingRecords[] = $this->service->transformTrackedUser($trackingRecord);
        }

        if (count($trackingRecords) == 0)
            return Response::responseNotFound();

        return Response::response($transformedTrackingRecords);
    }

    public function historyIndex() {
        $filter = Input::get();

        if (!isset($filter['userId']))
            return Response::responseMissingParameter();

        $userId = $filter['userId'];

        $startDate = strtotime("-1 day");
        $endDate = strtotime("now");

        if (isset($filter['startDate']))
            $startDate = strtotime($filter['startDate']);

        if (isset($filter['endDate']))
            $endDate = strtotime($filter['endDate']);

        $records = $this->service->getUserTrackingHistory($userId, $startDate, $endDate);

        return Response::response($records);
    }

    public function show(Request $request, $userId)
    {
        $locations = $this->service->getUserTrackingLocations($userId);
        return Response::response($locations);
    }

    public function store(Request $request)
    {
        $token = JWTAuth::getToken();
        $user = JWTAuth::authenticate($token);

        $trackingLocationsInfo = $request->get('tracking_locations');

        $data = [];
        $errorMsg = [];

        if (empty($trackingLocationsInfo))
        {
            return Response::response($data, 400, 'Data is empty.');
        }
        
        foreach ($trackingLocationsInfo as $info)
        {
            if (isset($user->id))
                $info['user_id'] = $user->id;
            //validate
            $validator = $this->service->validateInfo($info);
            if ($validator->fails())
            {
                $errors = $validator->errors()->all();
                $errorMsg = array_merge($errorMsg, $errors);
                return Response::responseValidateFailed(implode(' | ', $errorMsg));
            }

            $data[] = $this->service->insert($info);
        }

        if (!empty($errorMsg))
        {
            return Response::responseValidateFailed(implode(' | ', $errorMsg), $data);
        }

        return Response::response($data);
    }
}
