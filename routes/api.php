<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/

$app->group(['prefix' => 'v1', 'middleware' => 'api'], function() use($app) {

    // UNAUTHENTICATED ROUTES
    $app->post('token', 'TokenController@generateToken');
    $app->post('users', 'UserController@createUser');


    $app->group(['middleware' => 'auth'], function() use($app) {

        $app->get('get', function() {
            return "['data': {'id': 1}]";
        });

        // SCHOOL ROUTES
        $app->get('schools', 'SchoolController@getSchools');

        // USER ROUTES

        $app->get('users', 'UserController@getUser');



        // $app->group(['prefix' => 'user'], function($app) {
        //
        //     $app->get('get', function() {
        //         return 1;
        //     });
        //
        //     // EXAMLE ROUTES
        //     // $app->get('book','BookController@index');
        //     // $app->get('book/{id}','BookController@getbook');
        //     // $app->post('book','BookController@createBook');
        //     // $app->put('book/{id}','BookController@updateBook');
        //     // $app->delete('book/{id}','BookController@deleteBook');
        //
        // });

    });

});
