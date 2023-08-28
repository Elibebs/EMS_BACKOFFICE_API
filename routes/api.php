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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Socket Server
 */
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('socket/clients','ClientController@socketClients')->name('socket.clients');

Route::post('client/create','ClientController@create')->name('client.create');
Route::post('client/{uid}/update','ClientController@update')->name('client.update');
Route::get('client/{uid}/delete','ClientController@delete')->name('client.delete');
Route::post('client/{uid}/logo/upload','ClientController@uploadLogo')->name('client.logo.upload');
Route::get('client/{uid}/logo','ClientController@logo')->name('client.logo');

Route::post('setup/location/create','SetupController@createLocation')->name('setup.location.create');

Route::post('setup/site/create','SetupController@createSite')->name('setup.site.create');
Route::get('sites/{serial_number}','SetupController@getSiteBySerialNumber')->name('setup.site.serial_number');
Route::post('setup/site/{uid}/sources','SetupController@addSiteSources')->name('setup.site.sources.add');

Route::get('app/client/version/code','AppConfigController@getClientVersionCode')->name('app.config.version.code');
Route::post('app/client/version/code','AppConfigController@addClientVersionCode')->name('app.config.version.code');
