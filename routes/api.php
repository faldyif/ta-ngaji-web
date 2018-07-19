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
    Route::post('logout', 'Auth\LoginController@logout'); // Logout
    Route::put('firebase/token', 'FirebaseController@updateFirebaseToken'); // Update firebase token
    //-- End Authentication Routes --//

    //-- Begin Own User Routes --//
    Route::get('profile', 'UserController@showOwnProfile')->name('user.profile');
    //-- End Own User Routes --//

    //-- Begin User Routes --//
    Route::resource('users', 'UserController');
    //-- End Event Routes --//

    Route::name('teacher.')->prefix('teacher')->namespace('Teacher')->group(function () {

        Route::resource('freetime', 'TeacherFreeTimeController');

        Route::resource('event', 'EventController');
        Route::get('list/event/unconfirmed', 'EventController@indexUnconfirmed');
        Route::get('list/event/unconfirmed/count', 'EventController@countUnconfirmed');
        Route::get('list/event/confirmed', 'EventController@indexUpcoming');
        Route::post('update/event/status', 'EventController@changeStatus');
        Route::post('update/event', 'EventModificationRequestController@store');
        Route::post('update/event/respond', 'EventModificationRequestController@respondRequest');
        Route::get('event/modreq/count', 'EventModificationRequestController@countUnconfirmed');
        Route::post('event/check/presence', 'EventController@checkPresence');

    });

    //-- Begin Event Routes --//
    Route::resource('events', 'EventController');
    Route::get('history/events', 'EventController@indexHistory');
    Route::get('history/study/tahsin', 'EventController@indexStudyTahsinHistory');
    Route::get('history/study/tahfidz', 'EventController@indexStudyTahfidzHistory');
    Route::get('history/teaching', 'EventController@indexTeachingHistory');
    Route::get('events/filter/all', 'EventController@indexFiltered');
    Route::get('list/events/2hours', 'EventController@showMinus2Hours');
    Route::post('presence', 'EventController@attend');

    Route::post('update/event', 'EventModificationRequestController@store');
    Route::post('update/event/respond', 'EventModificationRequestController@respondRequest');
    Route::post('update/event/rate', 'EventController@giveRating');
    //-- End Event Routes --//

    //-- Begin Teacher Free Time Routes --//
    Route::get('finder/events', 'TeacherFreeTimeController@index');
    Route::get('finder/events/filter', 'TeacherFreeTimeController@indexFiltered');
    //-- End Event Routes --//

    Route::get('user', function (Request $request) {
        return $request->user();
    });
    Route::post('profile/update', 'UserController@updateProfile');


});
Route::get('test', 'Api\TestingController@test');
