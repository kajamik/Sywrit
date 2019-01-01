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

Auth::routes();

// Editoria
Route::group(['prefix' => 'publishers'], function() {
  Route::get('/', 'EditoriaController@index');
  Route::get('results', ['uses' => 'EditoriaController@getResults', 'as' => 'results']);
  Route::get('start', 'EditoriaController@getCreaEditoria')->middleware('auth');
  Route::post('start', 'EditoriaController@postCreaEditoria')->middleware('auth');
  Route::get('{slug}', 'EditoriaController@getEditoria');
  Route::get('{slug}/join/{token}', 'EditoriaController@getInvite');
});

Route::get('/', 'FrontController@index');
