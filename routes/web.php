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
  /*Route::get('{slug}/join/{token}', 'EditoriaController@getInvite');*/
});

Route::get('follow','AjaxController@follow');
// Group Actions
Route::post('group/invite', ['uses' => 'FilterController@inviteGroup', 'as' => 'group/action/invite']);
Route::post('group/leave', ['uses' => 'FilterController@leaveGroup', 'as' => 'group/action/leave']);
Route::post('group/delete', ['uses' => 'FilterController@deleteGroup', 'as' => 'group/action/delete']);
///////
Route::get('read/{slug}', 'FrontController@getArticle')->middleware('published');
// Article Actions
Route::post('post/publish', ['uses' => 'FilterController@ArticlePublish', 'as' => 'article/action/publish']);
Route::post('post/revision', ['uses' => 'FilterController@ArticlePublish', 'as' => 'article/action/revision']);
Route::post('post/edit', ['uses' => 'FilterController@ArticleEdit', 'as' => 'article/action/edit']);
Route::post('post/delete', ['uses' => 'FilterController@ArticleDelete', 'as' => 'article/action/delete']);
///////////
Route::group(['prefix' => 'write', 'middleware' => 'auth'], function(){
  Route::get('/', 'FrontController@getWrite');
  Route::post('/', 'FrontController@postWrite');
});

Route::get('/', 'FrontController@index');
Route::get('settings', ['uses' => 'FrontController@getSettings', 'as' => 'settings']);
Route::post('settings', 'FilterController@postSettings');
Route::post('change_username', ['uses' => 'FilterController@postChangeUsername', 'as' => 'settings/username']);
Route::post('change_password', ['uses' => 'FilterController@postChangePassword', 'as' => 'settings/password']);

// Profile
Route::get('{slug}', 'FrontController@getProfile');
Route::get('{slug}/archive', 'FrontController@getPrivateArchive')->middleware('auth');
