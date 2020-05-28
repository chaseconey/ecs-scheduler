<?php

use Aws\MultiRegionClient;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('logout', 'Auth\LogoutController@logout')->name('logout');
Route::get('auth/auth0', 'Auth\Auth0LoginController@redirectToProvider')->name('login');
Route::get('auth/auth0/callback', 'Auth\Auth0LoginController@handleProviderCallback');
Route::get('health', 'HealthController@index');

Route::group(['middleware' => 'conditional.auth'], function () {
    Route::get('/', 'ClusterController@index')->name('clusters.index');

    Route::post('/services/{id}/power', 'ServiceController@power')->name('services.power');
    Route::post('/services/{id}/schedule', 'ServiceController@schedule')->name('services.schedule');

    Route::resource('clusters', 'ClusterController')->except('index');
    Route::resource('services', 'ServiceController')->only('show', 'store', 'destroy');
});
