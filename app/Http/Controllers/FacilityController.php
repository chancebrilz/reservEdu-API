<?php

namespace App\Http\Controllers;

use Auth;
use Exception;
use App\School;
use App\Facility;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FacilityController extends Controller {

    public function createFacility(Request $request) {

        $this->validate($request, [
            'name' => 'required',
            'meta.description',
            'meta.price.amount' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'meta.price.per' => 'in:hr,day',
            'meta.availability.starting_date' => 'required|date',
            'meta.availability.ending_date' => 'required|date',
            'meta.availability.starting_time' => 'required|date_format:h:iA',
            'meta.availability.ending_time' => 'required|date_format:h:iA'
        ], [], [
            'meta.description' => 'description',
            'meta.price.amount' => 'price amount',
            'meta.price.per' => 'price per',
            'meta.availability.starting_date' => 'starting date',
            'meta.availability.ending_date' => 'ending data',
            'meta.availability.starting_time' => 'starting time',
            'meta.availability.ending_time' => 'ending time'
        ]);

        $request->school_id = json_decode(Auth::user()->meta)->school_id;

        try {


            $facility = Facility::create([
                'school_id' => $request->school_id,
                'name' => $request->name,
                'meta' => json_encode($request->meta)
            ]);

            return response()->json( $this->formatFacility($facility) );

        } catch(Exception $e) {
            return response()->json(['errors' => [[
                'details' => 'An unknown error occured.',
                'meta' => $e->getMessage()
            ]]], 422);
        }

    }

    public function getFacilities() {
        $facilities = Facility::where('school_id', '=', json_decode(Auth::user()->meta)->school_id )->get();
        foreach($facilities as $facility) {
            $facility = $this->formatFacility($facility);
        }
        return response()->json($facilities);
    }

    public function formatFacility($facility) {
        $facility->meta = json_decode($facility->meta);
        $facility->school = School::where('id', '=', $facility->school_id)->first();
        return $facility;
    }

    public function failedResponse() {
        return response()->json([

        ], 401);
    }

    public function successResponse() {
        return response()->json([

        ]);
    }

}
