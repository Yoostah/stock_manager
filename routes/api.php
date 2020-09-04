<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/live', function(){
    return ['status' => 'OK'];
});

Route::get('/401', 'AuthController@unauthorized')->name('login');

/** ACESSO SEM TOKEN **/
Route::post('/auth/login', 'AuthController@login');
Route::post('/user', 'UserController@store');

/** ACESSO COM TOKEN **/
Route::post('/auth/logout', 'AuthController@logout');
Route::post('/auth/refresh', 'AuthController@refresh');

// ## USUÁRIOS ##
// Route::get('/user/list', 'UserController@index');
// Route::get('/user', 'UserController@show');
// Route::get('/user/{id}', 'UserController@show');
// Route::delete('/user/{id}', 'UserController@destroy');
Route::put('/user/{id}/admin', 'UserController@setAdmin');
Route::put('/user', 'UserController@update');
Route::post('/user/avatar', 'UserController@updateAvatar');

// ## STATUS ##
// Route::get('/status', 'StatusControler@index');
// Route::get('/status/{id}', 'StatusControler@show');
// Route::post('/status', 'StatusControler@store');
// Route::delete('/status/{id}', 'StatusControler@destroy');
// Route::put('/status', 'StatusControler@update');

// ## ITEMS ##
// Route::get('/item', 'ItemControler@index');
// Route::get('/item/{id}', 'ItemControler@show');
// Route::post('/item', 'ItemControler@store');
// Route::delete('/item/{id}', 'ItemControler@destroy');
// Route::put('/item', 'ItemControler@update');

// ## CATEGORIA DE PRODUTOS ##
// Route::get('/category', 'CategoryControler@index');
// Route::get('/category/{id}', 'CategoryControler@show');
// Route::post('/category', 'CategoryControler@store');
// Route::delete('/category/{id}', 'CategoryControler@destroy');
// Route::put('/category', 'CategoryControler@update');

// ## STOCK ##
// Route::get('/stock/{id}', 'StockControler@show');
// Route::post('/stock', 'StockControler@store');
// Route::put('/stock', 'StockControler@update');

// ## BUSCA ##
// Route::get('/search', 'SearchControler@search');
