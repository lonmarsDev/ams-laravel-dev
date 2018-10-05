<?php

Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function () {

    /*
     * ---------------
     * Organisers
     * ---------------
     */


    /*
     * ---------------
     * Events
     * ---------------
     */
    Route::resource('events', 'API\EventsApiController');


    /*
     * ---------------
     * Attendees
     * ---------------
     */
    Route::resource('attendees', 'API\AttendeesApiController');


    /*
     * ---------------
     * Orders
     * ---------------
     */

    /*
     * ---------------
     * Users
     * ---------------
     */

    /*
     * ---------------
     * Check-In / Check-Out
     * ---------------
     */


    Route::get('/', function () {
        return response()->json([
            'Hello' => Auth::guard('api')->user()->full_name . '!'
        ]);
    });


});






/*
 * API V2 Routes
*/
Route::group(['prefix' => 'api_v2'], function () {

    Route::post('/auth/login',  'API_V2\Api2Controller@APILogin');
    
    Route::post('/auth/signup',  'API_V2\Api2Controller@APISignup');

    Route::get('/dealer/login', 'API_V2\Api2Controller@APIChangepassword');

    //middleware with JWT TOken
    Route::group(['middleware' => 'jwt-auth'], function () {

       Route::post('/auth/changepassword', 'API_V2\Api2Controller@APIChangepassword');
    
    });
    
});

