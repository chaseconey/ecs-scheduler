<?php

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

Route::get('test', function () {
    $client = new Aws\Ecs\EcsClient([
        'version' => '2014-11-13',
        'region' => 'us-west-2'
    ]);

    $result = $client->describeClusters();

    dd($result);
});

Route::get('/', function () {
    return view('welcome');
});
