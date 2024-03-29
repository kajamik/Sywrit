<?php

/** Web Routes **/

Auth::routes();

// Editoria
Route::group(['prefix' => 'publishers'], function() {
  Route::get('results', ['uses' => 'EditoriaController@getResults', 'as' => 'results']);
});

Route::group(['prefix' => '/'], function() {
  Route::get('{slug}/about', 'FrontController@getPublisherAbout');
  Route::group(['prefix' => '{slug}/settings', 'middleware' => 'auth'], function() {
    Route::get('/', 'FrontController@getPublisherSettings');
    Route::get('{tab}', 'FrontController@getPublisherSettings');
    Route::post('{tab}', 'FilterController@postPublisherSettings');
  });
  Route::get('{slug}/join/{token}', 'EditoriaController@getInvite');
});

Route::get('follow','AjaxController@follow');
Route::post('leave', ['uses' => 'FilterController@leaveGroup', 'as' => 'group/action/leave']);

//forum
/*Route::group(['prefix' => 'forum'], function(){
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
*/

Route::get('read/{slug}', 'FrontController@getArticle')->middleware('published');
Route::group(['prefix' => 'write', 'middleware' => 'auth'], function(){
  Route::get('/', 'FrontController@getWrite');
  Route::post('/', 'FrontController@postWrite');
});

Route::get('/', 'FrontController@index');
Route::get('settings', ['uses' => 'FrontController@getSettings', 'as' => 'settings']);
Route::post('settings', 'FilterController@postSettings');
Route::post('username', ['uses' => 'FilterController@postChangeUsername', 'as' => 'settings/username']);
Route::post('password', ['uses' => 'FilterController@postChangePassword', 'as' => 'settings/password']);

// Profile
Route::get('{slug}', 'FrontController@getProfile');
Route::get('{slug}/archive', 'FrontController@getPrivateArchive')->middleware('auth');
