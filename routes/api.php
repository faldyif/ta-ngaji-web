<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Authentication routes
Route::post('register', 'Api\Auth\RegisterController@register');
Route::post('login', 'Api\Auth\LoginController@login');
Route::post('refresh', 'Api\Auth\LoginController@refresh');


// Middleware 'onlyJsonApi', 'auth:api'
Route::middleware(['auth:api'])->name('api.')->prefix('v1')->namespace('Api')->group(function () {

    //-- Begin Own User Routes --//
    Route::get('profile', 'UserController@showOwnProfile')->name('user.profile');
    //-- End Own User Routes --//

    //-- Begin User Routes --//
    Route::resource('users', 'UserController');
    //-- End Event Routes --//

    //-- Begin Event Routes --//
    Route::resource('events', 'EventController');
    Route::get('events/{event}/relationships/teacher', 'EventRelationshipController@teacher')->name('events.relationships.teacher');
    Route::get('events/{event}/teacher', 'EventRelationshipController@teacher')->name('events.teacher');
    //-- End Event Routes --//

    Route::get('user', function (Request $request) {
        return $request->user();
    });

    Route::get('test', 'TestingController@test');

});