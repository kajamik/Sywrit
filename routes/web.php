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

Route::group(['prefix' => 'start', 'middleware' => 'auth'], function() {
  Route::get('/', 'EditoriaController@getStartPublisher');
  Route::get('offer', ['uses' => 'EditoriaController@getOfferSelected', 'as' => 'offer']);
  Route::post('offer', 'EditoriaController@changeRegistrationState');
});

// Editoria
Route::group(['prefix' => 'publishers'], function() {
  Route::get('/', 'EditoriaController@index');
  Route::get('results', ['uses' => 'EditoriaController@getResults', 'as' => 'results']);
});

Route::group(['prefix' => 'publisher'], function() {
  Route::get('{slug}', 'EditoriaController@getEditoria');
  Route::get('{slug}/{slug2}', 'EditoriaController@getEditoria');
  Route::get('{slug}/join/{token}', 'EditoriaController@getInvite');
});

Route::get('read/{slug}', 'FrontController@getArticle');
Route::get('/', 'FrontController@index');
