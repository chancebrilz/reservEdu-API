<?php

namespace App\Http\Controllers;

use Exception;
use App\User;
use Validator;
use Illuminate\Http\Request;

class UserController extends Controller {

    public function createUser(Request $request) {

        $this->validate($request, [
            'name' => 'required|alpha_dash',
            'useranme' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        try {

            $data = $request['data']['attributes'];

            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => app('hash')->make($request['password'])
            ]);

            return response()->json([
                'success' => 'User successfuly created!'
            ]);


        } catch(Exception $e) {

            return response()->json(['errors' => [[
                'details' => 'An unknown error occured.',
                'meta' => $e->getMessage()
            ]]], 422);

        }


    }

    public function failedResponse() {
        return response()->json([
            'error' => 'invalid_user',
            'error_message' => 'Authentication failed.'
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
