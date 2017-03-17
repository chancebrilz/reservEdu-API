<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use Illuminate\Http\Request;

class TokenController extends Controller {

    public function generateToken(Request $request) {

        if($this->validator($request)->fails()) {
            return $this->failedResponse();
        }

        $user = User::where('email', '=', $request->username)->first();

        if($user === null) {
            return $this->failedResponse();
        }

        if( app('hash')->check( $request->password, $user->password ) ) {
            return $this->successResponse($user->api_token);
        } else {
            return $this->failedResponse();
        }

    }

    public function failedResponse() {
        return response()->json([
            'error' => 'invalid_user',
            'error_message' => 'The username and password does not match.'
        ], 401);
    }

    public function successResponse($api_token) {
        return response()->json([
            'access_token' => $api_token
        ]);
    }

    public function validator($request) {
        return Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);
    }

}
