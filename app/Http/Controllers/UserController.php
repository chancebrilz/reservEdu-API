<?php

namespace App\Http\Controllers;

use Auth;
use Exception;
use App\User;
use Validator;
use Illuminate\Http\Request;

class UserController extends Controller {

    public function createUser(Request $request) {

        $this->validate($request, [
            'name' => 'required',
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

    public function getUserFromToken() {
        $user = Auth::user()->first();
        return response()->JSON(['data' => [
            'id' => $user->id,
            'attributes' => [
                'name' => $user->name,
                'email' => $user->email,
                'permissions' => json_decode($user->permissions)
            ],
            'type' => 'user'
        ]]);
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
