<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use App\Notifications\VerifyEmail as VerifyEmailNotification;
//use App\Traits\Achiever;

use App\Support\Database\CacheQueryBuilder;

class User extends Authenticatable
{
    protected $table = 'utenti';

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'surname', 'email', 'password', 'slug', 'avatar', 'social_auth_id', 'verified',
        'rank', 'points', 'followers_count', 'notifications_count', 'permission'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
      // Your your own implementation.
      $this->notify(new ResetPasswordNotification($token, $this->name));
    }

    /*public function sendEmailVerificationNotification()
    {
      $this->notify(new VerifyEmailNotification);
    }*/

    public function isOperator() {
      return ($this->permission > 1);
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

    public function getBackground() {
      if($this->copertina) {
        return $this->copertina;
      }
      return asset('upload/bg.jpg');
    }

    public function getAvatar() {
      if($this->avatar) {
        return $this->avatar;
      }
      return asset('upload/default.png');
    }

    public function haveGroup() {
      $components = collect(explode(',', $this->id_gruppo))->filter(function ($value, $key) {
        return $value != "";
      });
      return $components->isNotEmpty();
    }

    public function hasFoundedGroup() {
      if(\DB::table('editori')->where('direttore', $this->id)->count()) {
        return true;
      }
      return false;
    }

    public function getPublishersInfo() {
      if($this->haveGroup()) {
        $collection = collect();
        $components = collect(explode(',', $this->id_gruppo))->filter(function ($value, $key) {
          return $value != "";
        });
        foreach($components as $value) {
          $query = \DB::table('editori')->where('id', $value)->select('id', 'name', 'slug', 'suspended')->first();
          $collection->push($query);
        }
        return $collection->toArray();
      }
    }

    public function hasMemberOf($id) {
      if($this->haveGroup()) {
        foreach($this->getPublishersInfo() as $value) {
          if($value->id == $id) {
            return true;
          }
        }
      }
      return false;
    }

    public function getRealName() {
      $str = $this->name .' '. $this->surname. ' ';
      if($this->verified) {
        $str .= '<div class="sw-ico">';
        $str .= '<i class="sw_dark fas fa-check-circle" title="Profilo verificato&#xA;Sywrit ha confermato l\'autenticità di questo profilo."></i>';
        $str .= '</div>';
      }
      return $str;
    }

    /*public function getRankName() {
      $name = '';

      if($this->rank < 20){
        $name = 'principiante';
      }elseif($this->rank < 50){
        $name = 'intermedio';
      }elseif($this->rank < 101){
        $name = 'avanzato';
      }else{
        $name = 'maestro';
      }

      return $name;
    }

    public function UnlinkOldImage($file) {
      if(File::exists($this->storage.'/'.$file)){
        File::delete($this->storage.'/'.$file);
      }
    }*/

}
