<?php

namespace App\Http\Controllers;

use DB;
use Exception;
use App\School;
use Validator;
use Illuminate\Http\Request;

class SchoolController extends Controller {

    public function getSchools() {
        return response()->json(School::all());
    }

    public function getClosestSchools(Request $request) {

        $location = [
            'lat' => ($request['lat']) ? $request['lat'] : 0,
            'lng' => ($request['lng']) ? $request['lng'] : 0
        ];

        $query = "SELECT *, (3959 * acos(cos(radians('".$location['lat']."')) * cos(radians(lat)) * cos( radians(lng) - radians('".$location['lng']."')) + sin(radians('".$location['lat']."')) * sin(radians(lat)))) AS distance FROM schools HAVING distance < 50 ORDER BY distance LIMIT 0 , 10";

        $schools = DB::select($query);


        return response()->json($schools);
    }

    public function getSchoolFromCode($code) {
        $school = School::where('code', '=', $code)->first();
        if($school) {
            return $this->successResponse($school);
        } else {
            return $this->failedResponse("School not found!", 422);
        }
    }

    public function getSchoolFromId($id) {
        $school = School::where('id', '=', $id)->first();
        if($school) {
            return response()->json($school);
        } else {
            return response()->json([
                'error' => 'invalid_id',
                'error_message' => 'School not found with that id.'
            ], 404);
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
