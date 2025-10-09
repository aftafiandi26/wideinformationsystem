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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

// Holiday API Routes
Route::prefix('holidays')->group(function () {
    Route::get('year/{year}', 'HolidayController@getHolidaysByYear');
    Route::get('check/{date}', 'HolidayController@checkHoliday');
    Route::get('range/{startDate}/{endDate}', 'HolidayController@getHolidaysInRange');
    Route::get('month/{year}/{month}', 'HolidayController@getHolidaysByMonth');
    Route::get('total/{year}', 'HolidayController@getTotalHolidays');
    Route::get('available-years', 'HolidayController@getAvailableYears');
});