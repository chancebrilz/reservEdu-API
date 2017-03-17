<?php

namespace App\Http\Controllers;

use Exception;
use App\School;
use Validator;
use Illuminate\Http\Request;

class SchoolController extends Controller {

    public function getSchools() {
        return response()->json([
            'data' => $this->JSONFormat(School::all(), 'school')
        ]);
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
