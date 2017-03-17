<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('{path:.*}', function($path) use($app) {
    return response('HTTP 404 Not Found', 404);
});

// allow cross domain CORS verification
$app->options('{path:.*}', function($path) use($app) {
    return response('valid', 200);
});
