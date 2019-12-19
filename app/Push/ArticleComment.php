<?php

namespace App\Push;

// PushNotification::create(title, body, sender, receiver);
// PushNotification::get(ArticleComment::first());
class ArticleComment extends PushNotifications
{

    public $table = 'article_comments';

    public function __construct($class_name)
    {

    }
}
