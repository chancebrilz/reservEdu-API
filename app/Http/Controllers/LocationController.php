<?php

namespace App\Http\Controllers;

use Exception;
use App\Location;
use Validator;
use Illuminate\Http\Request;

class LocationController extends Controller {

    public function getLocations() {
        return response()->json(Location::all());
    }

    public function failedResponse() {
        return response()->json([

        ], 401);
    }

    public function successResponse() {
        return response()->json([

        ]);
    }

    public function validator($request) {
        return Validator::make($request->all(), [

        ]);
    }

}
