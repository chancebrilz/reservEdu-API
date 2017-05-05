<?php

namespace App\Http\Controllers;

use Auth;
use Exception;
use App\User;
use App\Facility;
use App\Reservation;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReservationController extends Controller {

    public function createReservation(Request $request) {

        return;

    }

    public function getReservations(Request $request) {

        $facilities = Facility::where('school_id', '=', json_decode(Auth::user()->meta)->school_id)->get();

        $reservations = [];

        foreach($facilities as $facility) {
            $_reservations = Reservation::where('facility_id', '=', $facility->id)->get();
            foreach($_reservations as $reservation) {
                $reservation->facility = $this->getFacility($reservation);
                $reservation->user = $this->getUser($reservation);
                $reservations[] = $reservation;
            }
        }

        return $reservations;

    }

    public function getFacility($reservation) {

        return Facility::where('id', '=', $reservation->facility_id)->first();

    }

    public function getUser($reservation) {

        return User::where('id', '=', $reservation->user_id)->first();

    }


}
