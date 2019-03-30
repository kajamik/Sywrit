<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ProfileComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $user;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose()
    {
        \View::composer('front.pages.*', function($view)
        {
          $group = array();
          foreach($this->user->getPublishersInfo() as $value) {
            $group[] = $value;
          }
        });
    }

}
