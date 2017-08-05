<?php

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

Route::get('/', function () {
    return view('home');
})->name('home');

//Route::group(['prefix' => 'v1'], function () {
//    Route::get('/user/sign-up', 'UsersController@create');
//    Route::get('register', 'RegistrationController@create');
//    Route::post('register', 'RegistrationController@store');
//});

Route::group(['prefix' => 'v1/user'], function () {
    Route::post('/sign-up', 'RegistrationController@register');    //DONE
});
