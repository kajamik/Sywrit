<?php

/** Web Routes **/

Auth::routes();

// Toolbox (Gestione)
Route::group(['prefix' => 'toolbox', 'middleware' => 'operator'], function() {
  Route::get('/', 'OpController@home');
  Route::group(['prefix' => 'users', 'namespace' => 'Toolbox'], function(){
    Route::get('/', 'UserController@index');
    Route::get('{id}/sheet', 'UserController@getUserSheet');
    Route::get('lock_account', 'UserController@getLockAccount');
  });
  Route::group(['prefix' => 'publishers', 'namespace' => 'Toolbox'], function(){
    Route::get('/', 'PublisherController@index');
    Route::get('{id}/sheet', 'PublisherController@getPageSheet');
    Route::get('lock_publisher', 'PublisherController@getLockPublisher');
  });
  Route::group(['prefix' => 'reports_activity', 'namespace' => 'Toolbox'], function(){
    Route::get('/', 'ReportsActivityController@index');
    Route::get('view', 'ReportsActivityController@getView');
    Route::get('lock_report', 'ReportsActivityController@getLockReport');
  });
  Route::group(['prefix' => 'bot_message', 'namespace' => 'Toolbox'], function(){
    Route::get('/', 'BotMessageController@index');
    Route::get('view', 'BotMessageController@getMessage');
    Route::get('create', 'BotMessageController@getCreateMessage');
    Route::post('create', 'BotMessageController@postCreateMessage');
    Route::post('delete', 'BotMessageController@postDeleteMessage');
  });
});
/*******************/

// Search
Route::group(['prefix' => 'search'], function() {
  Route::get('{slug}', 'SearchController@getResults');
  // Advanced search
  Route::get('tag/{slug}', 'SearchController@getResultsByTagName');
});
Route::get('live_search', ['uses' => 'AjaxController@SearchLiveData', 'as' => 'live_search']);
Route::get('live_notif', ['uses' => 'AjaxController@getNotifications', 'as' => 'live_notif']);
// Home
Route::group(['prefix' => '/'], function() {
  Route::get('{slug}/about', 'FrontController@getAbout');
});

Route::group(['prefix' => 'read'], function() {
  Route::get('{slug}', 'FrontController@getArticle');
  Route::get('archive/{slug}', 'FrontController@getSavedArticle');
});

// Article Actions
Route::group(['middleware' => 'auth'], function(){
  Route::group(['middleware' => 'isSuspended'], function(){
    // Group Actions
    Route::get('group/action/invite', 'AjaxController@inviteGroup');
    Route::get('group/action/leave', 'AjaxController@leaveGroup');
    Route::post('group/{id}/delete', 'FilterController@deleteGroup');
    Route::get('group/user/promote', 'FilterController@promoteUser');
    Route::get('group/user/fired', 'FilterController@firedUser');
    // User action
    Route::get('user/report', ['uses' => 'FilterController@UserReport', 'as' => 'user/action/report']);
    ///////
    // Article Actions
    Route::post('post/publish', ['uses' => 'FilterController@ArticlePublish', 'as' => 'article/action/publish']);
    Route::get('post/{id}/edit', 'FrontController@getArticleEdit');
    Route::post('post/{id}/edit', 'FilterController@postArticleEdit');
    Route::post('post/delete', ['uses' => 'FilterController@ArticleDelete', 'as' => 'article/action/delete']);
    Route::get('post/report', ['uses' => 'FilterController@ArticleReport', 'as' => 'article/action/report']);
    // Other
    Route::get('send-comment', 'AjaxController@postComments');
    Route::get('send-answers', 'AjaxController@postAnswers');
    Route::get('comment/report', ['uses' => 'FilterController@CommentReport', 'as' => 'comment/action/report']);
    Route::get('acomment/report', ['uses' => 'FilterController@ACommentReport', 'as' => 'acomment/action/report']);
    Route::get('rate', 'AjaxController@rate');
    Route::get('notifications_delete', 'AjaxController@deleteAllNotifications');
    Route::get('notification_delete', 'AjaxController@deleteNotification');
    Route::get('request_accepted', 'AjaxController@acceptGroupRequest');
  });
  Route::get('getStateNotifications', 'AjaxController@getStateNotifications');
});

Route::get('load-comments', 'AjaxController@loadComments');
Route::get('load-answers', 'AjaxController@loadAnswers');

///////////
Route::group(['prefix' => 'write', 'middleware' => ['auth','isSuspended']], function(){
  Route::get('/', 'FrontController@getWrite');
  Route::post('/', 'FilterController@postWrite');
});

Route::get('/', 'FrontController@index');
Route::group(['middleware' => ['auth', 'isSuspended']], function(){
  Route::get('notifications', 'FrontController@getNotifications');
  //Route::get('achievement', 'FrontController@getAchievement');
  Route::get('settings', ['uses' => 'FrontController@getSettings', 'as' => 'settings']);
  Route::post('settings', 'FilterController@postSettings');
  Route::post('change_username', ['uses' => 'FilterController@postChangeUsername', 'as' => 'settings/username']);
  Route::post('change_password', ['uses' => 'FilterController@postChangePassword', 'as' => 'settings/password']);
  Route::get('account_delete', 'FrontController@getAccountDelete');
  Route::post('account_delete', 'FilterController@postAccountDelete');
});

Route::get('action/support', 'AjaxController@getSupportRequest');

// Topic
Route::get('topic/{slug}', 'FrontController@getTopic');

// Profile
Route::group(['prefix' => '{slug}'], function() {
  Route::get('/', 'FrontController@getProfile');
  Route::get('archive', 'FrontController@getPrivateArchive')->middleware('auth','isSuspended');
});

// Publishers
Route::group(['prefix' => 'publisher'], function() {

  Route::get('{slug}', 'FrontController@getPublisherIndex');
  Route::get('{slug}/about', 'FrontController@getPublisherAbout');
  Route::get('{slug}/archive', 'FrontController@getPublisherArchive')->middleware('auth','isSuspended');

  Route::group(['prefix' => '{slug}/settings', 'middleware' => ['auth','isSuspended']], function() {
    Route::get('/', 'FrontController@getPublisherSettings');
    Route::get('{tab}', 'FrontController@getPublisherSettings');
    Route::post('{tab}', 'FilterController@postPublisherSettings');
  });
});

// CREATE GROUP
Route::get('publisher/create', 'FrontController@getNewPublisher')->middleware('auth','isSuspended');
Route::post('publisher/create', 'FilterController@postNewPublisher')->middleware('auth','isSuspended');

// Pages
Route::get('page/{slug}', 'FrontController@getPages');
Route::get('page/{slug}/{slug2}', 'FrontController@getPages');

// Facebook auth
Route::get('auth/facebook/redirect', 'Auth\SocialController@redirectToProvider');
Route::get('auth/facebook/callback', 'Auth\SocialController@handleProviderCallback');

// auth
/*Route::get('recover/code', 'Auth\SecurityCodeController@getCheckCode');
Route::post('recover/code', ['uses' => 'Auth\SecurityCodeController@postCheckCode', 'as' => 'sCode']);*/

// Ajax Controller

Route::group(['prefix' => 'ajax'], function() {
    Route::get('auth', 'AjaxController@getAuth');
});

Route::fallback(function(){
  return response()->view('errors.404', [], 404);
});
