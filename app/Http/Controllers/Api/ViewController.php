<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\CountryStateHelper;
use App\Helpers\Response;

class ViewController extends Controller
{
    public function index()
    {

        return view('home');
    }

    public function show(Request $request, $country)
    {
        $countryStateHelper = new CountryStateHelper();

        $states = $countryStateHelper->getStates($country);

        if (count($states) == 0)
            return Response::responseNotFound();

        return Response::response($states);
    }
}
