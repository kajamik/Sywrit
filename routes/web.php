<?php

/** Web Routes **/

Auth::routes();

// Toolbox (Gestione)
Route::group(['prefix' => 'toolbox', 'middleware' => 'operator'], function() {
  Route::get('/', 'OpController@home');
  Route::group(['prefix' => 'users', 'namespace' => 'Toolbox'], function(){
    Route::get('/', 'UserController@index');
    Route::get('{id}/sheet', 'UserController@getUserSheet');
  });
  Route::group(['prefix' => 'pages', 'namespace' => 'Toolbox'], function(){
    Route::get('/', 'PagesController@index');
    Route::get('{id}/sheet', 'PagesController@getPageSheet');
  });
  Route::group(['prefix' => 'reports_activity', 'namespace' => 'Toolbox'], function(){
    Route::get('/', 'ReportsActivityController@index');
  });
});
/*******************/

// Search
Route::group(['prefix' => 'search'], function() {
  Route::get('{slug}', 'SearchController@getResults');
  // Advanced search
  Route::get('tag/{slug}', 'SearchController@getResultsByTagName');
  Route::get('articles/{slug}', 'SearchController@getResultsByArticlesName');
  Route::get('users/{slug}', 'SearchController@getResultsByUsersName');
});
Route::get('live_search', ['uses' => 'AjaxController@SearchLiveData', 'as' => 'live_search']);
Route::get('live_notif', ['uses' => 'AjaxController@getNotifications', 'as' => 'live_notif']);
// Home
Route::group(['prefix' => '/'], function() {
  Route::get('{slug}/about', 'FrontController@getAbout');
  Route::group(['prefix' => '{slug}/settings', 'middleware' => 'auth'], function() {
    Route::get('/', 'FrontController@getPublisherSettings');
    Route::get('{tab}', 'FrontController@getPublisherSettings');
    Route::post('{tab}', 'FilterController@postPublisherSettings');
  });
  /*Route::get('{slug}/join/{token}', 'EditoriaController@getInvite');*/
});

Route::get('read/{slug}', 'FrontController@getArticle')->middleware('published');

// Article Actions
Route::group(['middleware','auth'], function(){
  // Group Actions
  Route::post('group/invite', ['uses' => 'FilterController@inviteGroup', 'as' => 'group/action/invite']);
  Route::post('group/leave', ['uses' => 'FilterController@leaveGroup', 'as' => 'group/action/leave']);
  Route::post('group/delete', ['uses' => 'FilterController@deleteGroup', 'as' => 'group/action/delete']);
  Route::post('user/promote', ['uses' => 'FilterController@promoteUser', 'as' => 'group/user/promote']);
  Route::post('user/fired', ['uses' => 'FilterController@firedUser', 'as' => 'group/user/fired']);
  //Route::post('user/invite', ['uses' => 'FilterController@inviteUser', 'as' => 'group/user/invite']);
  ///////
  // Article Actions
  Route::post('post/publish', ['uses' => 'FilterController@ArticlePublish', 'as' => 'article/action/publish']);
  Route::get('post/{id}/edit', 'FrontController@getArticleEdit');
  Route::post('post/{id}/edit', 'FilterController@postArticleEdit');
  Route::post('post/delete', ['uses' => 'FilterController@ArticleDelete', 'as' => 'article/action/delete']);
  Route::post('post/report', ['uses' => 'FilterController@ArticleReport', 'as' => 'article/action/report']);
  // Other
  Route::get('follow', ['uses' =>'AjaxController@follow', 'as' => 'follow']);
  Route::get('like', ['uses' => 'AjaxController@like', 'as' => 'like']);
});

///////////
Route::group(['prefix' => 'write', 'middleware' => 'auth'], function(){
  Route::get('/', 'FrontController@getWrite');
  Route::post('/', 'FrontController@postWrite');
});

Route::get('/', 'FrontController@index');
Route::group(['middleware' => 'auth'], function(){
  Route::get('notifications', 'FrontController@getNotifications');
  Route::get('settings', ['uses' => 'FrontController@getSettings', 'as' => 'settings']);
  Route::post('settings', 'FilterController@postSettings');
  Route::post('change_username', ['uses' => 'FilterController@postChangeUsername', 'as' => 'settings/username']);
  Route::post('change_password', ['uses' => 'FilterController@postChangePassword', 'as' => 'settings/password']);
});

// Profile
Route::get('{slug}', 'FrontController@getProfile');
Route::get('{slug}/archive', 'FrontController@getPrivateArchive')->middleware('auth');

// CREATE GROUP
Route::get('publisher/create', 'FrontController@getNewPublisher')->middleware('auth');
Route::post('publisher/create', 'FilterController@postNewPublisher')->middleware('auth');

// Pages
Route::get('page/{slug}', 'FrontController@getPages');
