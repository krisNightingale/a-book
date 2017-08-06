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
    return view('welcome');
});

Route::group(['prefix' => 'v1/user'], function () {
    Route::post('/auth', 'SessionsController@signIn');           //DONE
    Route::post('/request-password-reset', 'PasswordsController@requestPasswordReset'); //TODO Mail
    Route::post('/set-password', 'PasswordsController@setPassword');                    //DONE
    Route::post('/set-password/by-token', 'PasswordsController@setPasswordByToken');    //DONE
});

Route::group(['prefix' => 'v1/session'], function () {
    Route::post('/expire', 'SessionsController@destroy');       //DONE
    Route::post('/prolong', 'SessionsController@prolong');      //DONE
    Route::post('/check', 'SessionsController@checkSession');    //DONE
});