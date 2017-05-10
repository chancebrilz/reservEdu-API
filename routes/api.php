<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/

$app->group(['prefix' => 'v1', 'middleware' => 'api'], function() use($app) {

    // UNAUTHENTICATED ROUTES
    $app->post('oauth2/token', 'TokenController@generateToken');
    $app->post('users', 'UserController@createUser');

    // SCHOOL ROUTES
    $app->get('schools/closest', 'SchoolController@getClosestSchools');
    $app->get('schools/{id}', 'SchoolController@getSchoolFromId');
    $app->get('schools/code/{code}', 'SchoolController@getSchoolFromCode');


    $app->group(['middleware' => 'auth'], function() use($app) {

        $app->get('get', function() {
            return "['data': {'id': 1}]";
        });

        // FACILITY ROUTES
        $app->get('facilities', 'FacilityController@getFacilities');
        $app->post('facilities', 'FacilityController@createFacility');

        // RESERVATION ROUTES
        $app->get('reservations', 'ReservationController@getReservations');
        $app->post('reservations', 'ReservationController@createReservation');

        // USER ROUTES
        $app->get('users', 'UserController@getUser');
        $app->get('users/token', 'UserController@getUserFromToken');
        $app->get('users/{id}', 'UserController@getUserFromId');

        $app->get('payments', 'PaymentController@getAllPayments');
        $app->get('payments/{id}', 'PaymentController@getPayment');


    });

});
