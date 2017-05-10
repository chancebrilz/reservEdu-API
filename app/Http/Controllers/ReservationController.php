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
use Illuminate\Support\Facades\Mail;

class ReservationController extends Controller {

    public function createReservation(Request $request) {

        //@validate information

        //@add reservation to database

        $user = Auth::user();

        $reservation = ['id' => 12321]; //placeholder

        // send email to DOE
        app('mailer')->send('mail.reservation', ['reservation' => $reservation], function($m) use($user) {
            $m->from('support@reservedu.com', 'reservEDU Team');
            $m->to('chancebrilz@gmail.com', 'Department of Education'); //to be changed to something like support@ed.gov
            $m->subject('New Reservation');
        });

        // return reservation model
        return response()->json();

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
