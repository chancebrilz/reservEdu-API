<?php

namespace App\Http\Controllers;

use Auth;
use Exception;
use App\User;
use App\School;
use Validator;
use Illuminate\Http\Request;

class UserController extends Controller {

    public function createUser(Request $request) {

        if($request['type'] == 'school') {

            // REGSITER SCHOOL

            $this->validate($request, [
                'code' => 'required',
                'password' => 'required|min:6'
            ]);

            try {

                $school = School::where('code', '=', $request['code'])->first();

                if($school) {

                    if($school['activated'] == false) {

                        $user = User::create([
                            'name' => $school['name'],
                            'email' => $school['email'],
                            'password' => app('hash')->make($request['password']),
                            'api_token' => str_random(60),
                            'permissions' => json_encode(['admin' => true])
                        ]);

                        $school->activated = true;
                        $school->save();

                        return response()->json($user);

                    } else {
                        return response()->json(['errors' => [[
                            'details' => 'That school has already been registered!',
                        ]]], 422);
                    }

                } else {
                    return response()->json(['errors' => [[
                        'details' => 'A school with that code could not be found. Try again!',
                    ]]], 422);
                }


            } catch(Exception $e) {
                return response()->json(['errors' => [[
                    'details' => 'An unknown error occured.',
                    'meta' => $e->getMessage()
                ]]], 422);
            }

        } else {

            //REGISTER PERSONAL ACCOUNT

            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6'
            ]);

            try {

                $user = User::create([
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'password' => app('hash')->make($request['password']),
                    'api_token' => str_random(60),
                    'permissions' => json_encode([])
                ]);

                return response()->json($user);

            } catch(Exception $e) {
                return response()->json(['errors' => [[
                    'details' => 'An unknown error occured.',
                    'meta' => $e->getMessage()
                ]]], 422);
            }
        }

    }

    public function getUserFromToken() {
        $user = Auth::user();
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'permissions' => json_decode($user->permissions)
        ]);
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

}
