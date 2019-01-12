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
  Route::post('offer', ['uses' => 'EditoriaController@postBePublisher', 'as' => 'offer/complete']);
});

// Editoria
Route::group(['prefix' => 'publishers'], function() {
  Route::get('/', 'EditoriaController@index');
  Route::get('results', ['uses' => 'EditoriaController@getResults', 'as' => 'results']);
});

Route::group(['prefix' => 'group'], function() {
  Route::get('{slug}', 'EditoriaController@getEditoria');
  Route::get('{slug}/{slug2}', 'EditoriaController@getEditoria');
  Route::get('{slug}/join/{token}', 'EditoriaController@getInvite');
});

Route::get('follow','AjaxController@follow');

//forum
Route::group(['prefix' => 'forum'], function(){
    Route::get('/', 'ForumController@index');
    Route::get('{slug}', 'ForumController@getSection');
    Route::post('{slug}/new', 'ForumController@postAddTopic');
    Route::get('topic/{slug}', 'ForumController@getTopic');
    Route::middleware(['auth'])->group(function(){
      Route::get('{slug}/new', 'ForumController@getAddTopic');
      Route::post('topic/{slug}', 'ForumController@postAnswerPost');
      Route::get('post/{id}/edit', 'ForumController@getEditPost');
      Route::post('post/{id}/edit', 'ForumController@postEditPost');
      Route::get('topic/{slug}/mods/{slug2}','ForumController@getActionMods')->middleware('admin');
    });
});

//assistenza
Route::group(['prefix' => 'support'], function(){
  Route::get('/', 'SupportController@index');
  Route::middleware(['auth'])->group(function(){
    Route::get('ticket/new', 'SupportController@getNewTicket');
    Route::post('ticket/new', 'SupportController@postNewTicket');
    Route::get('ticket/view/{slug}', 'SupportController@getViewTicket');
    Route::post('ticket/view/{slug}', 'SupportController@postAnswerTicket');
    Route::get('ticket/management', 'SupportController@getTicketManagement');
    Route::get('ticket/management/{id}/locked', 'SupportController@lockTicket');
  });
});

Route::group(['prefix' => 'profile'], function(){
    Route::get('{slug}', 'FrontController@getProfile');
    Route::get('{slug}/{slug2}', 'FrontController@getProfile');
});

Route::get('read/{slug}', 'FrontController@getArticle');
Route::group(['prefix' => 'write', 'middleware' => 'auth'], function(){
  Route::get('/', 'FrontController@getWrite');
  Route::post('/', 'FrontController@postWrite');
});

Route::get('/', 'FrontController@index');
