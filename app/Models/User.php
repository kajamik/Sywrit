<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'utenti';

    use Notifiable;

    private $storage = 'storage/accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'nome', 'cognome', 'email', 'password', 'slug',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

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
      $file = $this->storage.'/'.$this->copertina;
      if($this->copertina && file_exists($file))
        return $file;
      return 'upload/no-copertina.png';
    }

    public function getAvatar() {
      $file = $this->storage.'/'.$this->avatar;
      if($this->avatar && file_exists($file))
        return $file;
      return 'upload/default.png';
    }

    public function haveGroup() {
      return ($this->id_gruppo > 0);
    }

    public function isDirector() {
      return ($this->getPublisherInfo()->direttore == $this->id);
    }

    public function getPublisherInfo() {
      if($this->haveGroup()){
        $query = \DB::table('editori')->where('id',$this->id_gruppo)->first();
        return $query;
      }
    }

    public function getRankName() {
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

    /*public function UnlinkOldImage($file) {
      if(File::exists($this->storage.'/'.$file)){
        File::delete($this->storage.'/'.$file);
      }
    }*/

}
