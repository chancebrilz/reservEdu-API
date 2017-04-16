<?php

namespace App\Http\Controllers;

use Validator;
use App\Payment;
use Illuminate\Http\Request;

// stripe classes
use Stripe\Stripe;
use Stripe\Charge;

class PaymentController extends Controller {

    public function __construct(Request $request) {

        Stripe::setApiKey( env('STRIPE_API_KEY') );
    }

    public function getAllPayments() {

        $payments = Charge::all()['data'];
        $return_payments = [];
        $i = 0;

        foreach($payments as $payment) {
            $return_payments[$i] = [
                'id' => $i,
                'stripe' => $payment
            ];
            $i++;
        }

        return response()->json( $return_payments );
    }

    public function getPayments($payment_id) {
        return response()->json( Charge::retrieve($payment_id) );
    }

}
