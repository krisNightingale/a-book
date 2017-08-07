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

Route::group(['prefix' => 'v1/book'], function () {
    Route::get('/', 'BooksController@getBookList');         //DONE
    Route::post('/', 'BooksController@addBook');            //DONE
    Route::get('/search', 'BooksController@searchBook');    //DONE
});

Route::group(['prefix' => 'v1/user'], function () {
    Route::get('/me', 'UsersController@getCurrentUser');      //DONE
    Route::post('/me', 'UsersController@updateUserInfo');     //DONE
    Route::get('/{user_id}', 'UsersController@getUserById');  //DONE
});

Route::group(['prefix' => 'v1/admin/book'], function () {
    Route::post('/', 'AdminController@addBook');
    Route::delete('/', 'AdminController@deleteBook');
    Route::update('/{book_id}', 'AdminController@updateBook');
});