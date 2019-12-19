<?php

namespace App\Push;

use App\Models\Notification;

class PushNotification
{

    /*public function __construct($title, $body, $sender, $receiver)
    {
        $this->create(
          'sender_id' => $sender,
          'receiver_id' => $receiver
        );
    }*/

    public function create($title, $body, $sender, $receiver)
    {
        $query = new Notification();
        /*foreach($array as $key => $value) {
          $query[$key] = $value;
        }*/
        $query->sender_id = $sender;
        $query->receiver_id = $receiver;
        $query->save();
    }

    public function get($class_name)
    {
      
    }
}
