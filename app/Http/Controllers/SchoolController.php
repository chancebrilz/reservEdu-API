<?php

namespace App\Http\Controllers;

use Exception;
use App\School;
use Validator;
use Illuminate\Http\Request;

class SchoolController extends Controller {

    public function getSchools() {
        return response()->json(School::all());
    }

    public function getSchoolFromCode($code) {
        $school = School::where('code', '=', $code)->first();
        if($school) {
            return $this->successResponse($school);
        } else {
            return $this->failedResponse("School not found!", 422);
        }
    }

    public function failedResponse($message, $code) {
        return response()->json(['errors' => [[
            'details' => $message
        ]]], $code);
    }

    public function successResponse($data) {
        return response()->json($data);
    }

}
