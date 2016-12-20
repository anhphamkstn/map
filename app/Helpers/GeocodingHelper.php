<?php
namespace App\Helpers;

class GeocodingHelper
{
    public static function transform($geocodingResult)
    {
        $transformedResult = [];

        if (isset($geocodingResult['address_components'])) {
            $addressComponents = $geocodingResult['address_components'];
            $transformedResult['street'] = '';
            $streetSuffix = [];

            foreach ($addressComponents as $addressComponent) {
                if (in_array("postal_code", $addressComponent['types'])) {
                    $transformedResult['zip_code'] = $addressComponent['long_name'];
                } elseif (in_array("political", $addressComponent['types'])) {
                    if (in_array("country", $addressComponent['types'])) {
                        $transformedResult['country'] = $addressComponent['long_name'];
                    } elseif (in_array("administrative_area_level_1", $addressComponent['types'])) {
                        $transformedResult['state_province'] = $addressComponent['long_name'];
                    } elseif (in_array("administrative_area_level_2", $addressComponent['types'])) {
                        $transformedResult['district'] = $addressComponent['long_name'];
                    } elseif (in_array("sublocality", $addressComponent['types'])) {
                        $streetSuffix[] = $addressComponent['long_name'];
                    }
                } else {
                    $transformedResult['street'] .= $addressComponent['long_name'] . ', ';
                }
            }

            if (count($streetSuffix) > 0)
                $transformedResult['street'] .= join(', ', $streetSuffix);

            if (substr($transformedResult['street'], count($transformedResult['street']) - 3) == ', ') {
                $transformedResult['street'] = substr($transformedResult['street'], 0, count($transformedResult['street']) - 3);
            }
        }

        if (isset($geocodingResult['geometry'])) {
            if (isset($geocodingResult['geometry']['location'])) {
                $transformedResult['lat'] = $geocodingResult['geometry']['location']['lat'];
                $transformedResult['lon'] = $geocodingResult['geometry']['location']['lng'];
            }
        }
        if (isset($geocodingResult['name']) && isset($geocodingResult['vicinity'])) {
            $vprimary = true;
            $transformedResult['id'] = $geocodingResult['place_id'];
            $transformedResult['primary'] = $vprimary;
            $transformedResult['zip_code'] = "";
            $transformedResult['district'] = "";
            $transformedResult['state_province'] = "";
            $transformedResult['country'] = "";
            $transformedResult['street'] = $geocodingResult['vicinity'];
        }
        if (isset($geocodingResult['international_phone_number'])) {
            $transformedResult['phone_number'] = $geocodingResult['international_phone_number'];
        }
        if (isset($geocodingResult['name'])) {
            $transformedResult['name'] = $geocodingResult['name'];
        }


        return $transformedResult;
    }

    public static function getGoogleGeocodingApiByAddress($address, $curl = false)
    {
        $requestFields = [
            'address' => urlencode($address),
            'key' => env('GOOGLE_API_KEY', 'AIzaSyDaWcBKD09RsGlo3CDxOS70X7u8MxusDtM')
        ];

        $requestFieldsString = '';

        foreach ($requestFields as $key => $value) {
            $requestFieldsString .= $key . '=' . $value . '&';
        }
        rtrim($requestFieldsString, '&');

        return GeocodingHelper::getGoogleGeocodingApi($requestFieldsString, $curl);
    }

    public static function getAddressBoundsFromGoogleGeocodingApi($address, $curl = false)
    {
        $googleGeocodingResults = GeocodingHelper::getGoogleGeocodingApiByAddress($address);
        $googleGeocodingResults = json_decode($googleGeocodingResults, true);

        $result = ['northeast' => [], 'southwest' => []];

        if (!isset($googleGeocodingResults['results']))
            return $result;

        $googleGeocodingResult = $googleGeocodingResults['results'][0];

        if (!isset($googleGeocodingResult['geometry']) || !isset($googleGeocodingResult['geometry']['bounds']))
            return $result;

        $bounds = $googleGeocodingResult['geometry']['bounds'];

        $result = [
            'northeast' => [
                'lat' => $bounds['northeast']['lat'],
                'lon' => $bounds['northeast']['lng']
            ],
            'southwest' => [
                'lat' => $bounds['southwest']['lat'],
                'lon' => $bounds['southwest']['lng']
            ]
        ];

        return $result;
    }

    public static function getGoogleGeocodingApiByLatlng($latlng, $curl = false)
    {
        $requestFields = [
            'latlng' => urlencode($latlng),
            'key' => env('GOOGLE_API_KEY', 'AIzaSyDaWcBKD09RsGlo3CDxOS70X7u8MxusDtM')
        ];

        $requestFieldsString = '';

        foreach ($requestFields as $key => $value) {
            $requestFieldsString .= $key . '=' . $value . '&';
        }
        rtrim($requestFieldsString, '&');

        return GeocodingHelper::getGoogleGeocodingApi($requestFieldsString, $curl);
    }

    public static function getGoogleGeocodingApi($requestFieldsString, $curl = false)
    {
        $googleGeocodingApiUrl = 'https://maps.googleapis.com/maps/api/geocode/json?';

        if ($curl) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $googleGeocodingApiUrl . $requestFieldsString);
            $result = curl_exec($ch);
            curl_close($ch);
        } else
            $result = file_get_contents($googleGeocodingApiUrl . $requestFieldsString);

        return $result;
    }


    public static function getGooglePlacesApiPlaceDetail($placeId)
    {
        $googlePlacesApiKey = "AIzaSyDVj2X308UTSncs8kQ4_6qZOY9_ANF0r_Q";

        $googlePlacesApiParams = [
            'key' => 'key=' . $googlePlacesApiKey,
            'placeid' => 'placeid=' . $placeId
        ];

        $googlePlacesApiUrl = "https://maps.googleapis.com/maps/api/place/details/json?" . join('&', $googlePlacesApiParams);

        return json_decode(file_get_contents($googlePlacesApiUrl), true);
    }

    public static function getGoogleNearBySearch($nearBySearch)
    {
        $googlePlacesApiUrl = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?';
        $result = file_get_contents($googlePlacesApiUrl);

        return json_decode($result, true);
    }

    public static function getGoogleGeocodingApiByBusinessDirect($location, $type, $radius)
    {

        $googlePlacesApiKey = "AIzaSyDVj2X308UTSncs8kQ4_6qZOY9_ANF0r_Q";

        $googlePlacesApiParams = [

            'location' => 'location=' . $location,
            'type' => 'type=' . $type,
            'radius' => 'radius=' . $radius,
            'key' => 'key=' . $googlePlacesApiKey
        ];

        $googlePlacesApiUrl = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?" . join('&', $googlePlacesApiParams);

        $result = file_get_contents($googlePlacesApiUrl);
        return $result;
    }

    public static function getPlaceDetailFromGooglePlacesApi($placeId)
    {
        $googlePlacesApiKey = "AIzaSyDVj2X308UTSncs8kQ4_6qZOY9_ANF0r_Q";

        $googlePlacesApiParams = [
            'key' => 'key=' . $googlePlacesApiKey,
            'placeid' => 'placeid=' . $placeId
        ];

        $googlePlacesApiUrl = "https://maps.googleapis.com/maps/api/place/details/json?" . join('&', $googlePlacesApiParams);

        return json_decode(file_get_contents($googlePlacesApiUrl), true);
    }
}