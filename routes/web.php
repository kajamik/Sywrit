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
  Route::group(['prefix' => 'logs', 'namespace' => 'Toolbox'], function(){
    Route::get('/', 'LogsController@index');
  });
  Route::get('optimize', 'Toolbox\OptimizeController@index');
  Route::post('optimize', 'Toolbox\OptimizeController@toEmpty');
  Route::get('server/console/execute', 'Toolbox\OptimizeController@run');
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

// Pages
Route::get('page/about', function() { return view('front.pages.static.about'); });
Route::get('page/{slug}', 'FrontController@getPages');
Route::get('page/{slug}/{slug2}', 'FrontController@getPages');

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
    Route::get('post/publish', ['uses' => 'FilterController@ArticlePublish', 'as' => 'article/action/publish']);
    Route::get('article/{id}/action/edit', 'FrontController@getArticleEdit');
    Route::post('article/{id}/action/edit', 'FilterController@postArticleEdit');
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

Route::group(['prefix' => 'write', 'middleware' => ['auth','isSuspended']], function(){
  Route::get('/', 'FrontController@getWrite');
  Route::post('/', 'FilterController@postWrite');
});

Route::get('/', 'FrontController@index');

Route::group(['middleware' => ['auth', 'isSuspended']], function(){
  //Route::get('achievement', 'FrontController@getAchievement');
  Route::group(['prefix' => 'settings'], function() {
    Route::get('/', 'SettingController@index');
    Route::group(['prefix' => 'account'], function() {
      Route::get('/', 'SettingController@getAccount');
      Route::get('name', 'SettingController@getAccountName');
      Route::post('name', 'SettingController@postAccountName');
      /*Route::get('username', 'SettingController@getAccountUsername');
      Route::post('username', 'SettingController@postAccountUsername');*/
      Route::get('manage', 'SettingController@getAccountManage');
    });
    Route::get('change_language', 'SettingController@getChangeLanguage');
    Route::post('change_language', 'SettingController@postChangeLanguage');
    Route::get('change_password', 'SettingController@getChangePassword');
    Route::post('change_password', 'SettingController@postChangePassword');
  });
  Route::get('account_delete', 'FrontController@getAccountDelete');
  Route::post('account_delete', 'FilterController@postAccountDelete');
});

Route::get('action/support', 'AjaxController@getSupportRequest');

// Topic
Route::get('topic/{slug}', 'FrontController@getTopic');

// Manager article
Route::group(['prefix' => 'articles', 'middleware' => ['auth','isSuspended']], function() {
  Route::get('/', 'FrontController@getPrivateArchive');
  //--
  Route::get('schedule', 'FrontController@getScheduleArticle');
  Route::get('schedule/view/{id}', 'FrontController@getScheduleArticleView');
  Route::get('schedule/edit/{id}', 'FrontController@getScheduleArticleEdit');
  Route::post('schedule/edit/{id}', 'FilterController@postScheduleArticleEdit');
  Route::post('schedule/delete/{id}', 'FilterController@deleteScheduleArticle');
  //--
  Route::get('drafts', 'FrontController@getDraftArticle');
  //--
  Route::get('draft/view/{id}', 'FrontController@getDraftArticleView');
  Route::get('draft/edit/{id}', 'FrontController@getDraftArticleEdit');
  Route::post('draft/edit/{id}', 'FilterController@postDraftArticleEdit');
  Route::post('draft/delete/{id}', 'FilterController@deleteDraftArticle');
});

// Profile
Route::group(['prefix' => '{slug}'], function() {
  Route::get('/', 'FrontController@getProfile');
});

// Publishers
/*Route::group(['prefix' => 'groups'], function() {

  Route::get('{id}', 'GroupController@index');
  Route::get('{id}/about', 'GroupController@getAbout');
  Route::get('{id}/article/{post_id}', 'GroupController@getArticle');
  Route::get('{id}/article/{post_id}/edit', 'GroupController@getArticleEdit');
  Route::get('{id}/article/{post_id}/commits', 'GroupController@getArticleCommits');
  Route::get('{id}/article/{post_id}/commit/{commit_id}', 'GroupController@getArticleCommit');
  Route::get('{id}/members', 'GroupController@getMembers');
  Route::get('{id}/write', 'GroupController@getNewArticle');

  Route::post('{id}/write', 'GroupController@postNewArticle');
  Route::post('{id}/article/{post_id}/edit', 'GroupController@postArticleEdit');

  Route::namespace('GroupAdmin')->group(function() {
    Route::get('{id}/admin', 'SettingController@index');
    Route::get('{id}/admin/requests', 'RequestController@getJoinRequests');
  });

  /*Route::group(['prefix' => '{slug}/settings', 'middleware' => ['auth','isSuspended']], function() {
    Route::get('/', 'FrontController@getPublisherSettings');
    Route::get('{tab}', 'FrontController@getPublisherSettings');
    Route::post('{tab}', 'FilterController@postPublisherSettings');
  });*/
//});

// CREATE GROUP
//Route::get('group/create', 'GroupController@getNewGroup')->middleware('auth','isSuspended');
//Route::post('group/create', 'GroupController@postNewGroup')->middleware('auth','isSuspended');

// Facebook auth
Route::get('auth/facebook/redirect', 'Auth\SocialController@redirectToProvider');
Route::get('auth/facebook/callback', 'Auth\SocialController@handleProviderCallback');

// auth
/*Route::get('recover/code', 'Auth\SecurityCodeController@getCheckCode');
Route::post('recover/code', ['uses' => 'Auth\SecurityCodeController@postCheckCode', 'as' => 'sCode']);*/

// Ajax Controller
Route::group(['prefix' => 'ajax'], function() {

    Route::get('auth', 'AjaxController@getAuth');

    //Route::get('info', 'AjaxController@getWebData');

    //Route::get('notifications', 'FrontController@getNotifications');
    Route::get('notifications', 'NotificationController@check');

    Route::group(['prefix' => 'account'], function() {
      Route::get('add_social_address', 'AjaxController@getAddSocialAddress');
    });

    /*Route::group(['prefix' => 'groups'], function() {
      Route::get('loadMembers', 'AjaxController@loadMembers');
      Route::get('loadMessages', 'AjaxController@loadGroupMessage');
      Route::get('sendJoinRequest', 'AjaxController@joinGroupRequest');
      Route::get('joinResponse', 'AjaxController@joinGroupResponse');
      Route::get('sendMessage', 'AjaxController@sendGroupMessage');
      Route::get('member', 'AjaxController@userAction');
      Route::get('post', 'AjaxController@postAction');
      Route::get('writeArticle', 'AjaxController@writeGroupArticle');
    });*/

    Route::post('article/action/autosaving', 'AjaxController@autoSaving');
    Route::get('article/action/schedule', 'AjaxController@scheduleArticle');
    Route::get('article/action/delete/schedule', 'AjaxController@removeSchedule');

    // User Thumbnail
    //Route::get('thumbnail', 'AjaxController@getUserThumbnail');
});

Route::fallback(function(){
  return response()->view('errors.404', [], 404);
});
