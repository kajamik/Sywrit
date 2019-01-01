<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'Utenti';

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome', 'username', 'email', 'password', 'slug',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isAdmin() {
      if($this->permission >= 4)
        return true;
      else return false;
    }

    public function getRole() {
      if($this->permission == 1)
        return "Utente";
      elseif($this->permission == 2)
        return "Operatore";
      elseif($this->permission == 3)
        return "Moderatore";
      elseif($this->permission == 4)
        return "Amministratore";
      else
        return "";
    }

    public function getAvatar() {
      if($this->avatar == "")
        return "upload/default.png";
      return "storage/avatar/".$this->avatar;
    }
}
