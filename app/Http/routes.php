<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


$urls = config('urls');

foreach ($urls as $url) {
    Route::get($url['url'], ['as' => 'get_' . $url['url'], 'uses' => $url['controller'] . '@getIndex']);
    Route::post($url['url'], ['as' => 'post_' . $url['url'], 'uses' => $url['controller'] . '@postIndex']);
}


Route::any('/', function () use ($urls) {
    return redirect()->route('get_'.$urls[0]['url']);
});