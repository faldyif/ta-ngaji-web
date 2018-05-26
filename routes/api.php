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
Route::post('register', 'Api\Auth\RegisterController@register'); // Register
Route::post('login', 'Api\Auth\LoginController@login');
Route::post('refresh', 'Api\Auth\LoginController@refresh');


// Middleware 'onlyJsonApi', 'auth:api'
Route::middleware(['auth:api'])->name('api.')->prefix('v1')->namespace('Api')->group(function () {

    //-- Begin Authentication Routes --//
    Route::post('logout', 'Api\Auth\LoginController@logout'); // Logout
    //-- End Authentication Routes --//

    //-- Begin Own User Routes --//
    Route::get('profile', 'UserController@showOwnProfile')->name('user.profile');
    //-- End Own User Routes --//

    //-- Begin User Routes --//
    Route::resource('users', 'UserController');
    //-- End Event Routes --//

    //-- Begin Event Routes --//
    Route::resource('events', 'EventController');
    Route::get('events/filter/all', 'EventController@indexFiltered');
    //-- End Event Routes --//

    //-- Begin Teacher Free Time Routes --//
    Route::resource('finder/events', 'TeacherFreeTimeController');
    //-- End Event Routes --//

    Route::get('user', function (Request $request) {
        return $request->user();
    });

    Route::get('test', 'TestingController@test');

});