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
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/api/v1/books', 'BookController@index');
Route::get('/api/v1/books/{id}', 'BookController@getById');
Route::post('/api/v1/books', 'BookController@create');
Route::delete('/api/v1/books/{id}', 'BookController@delete');
