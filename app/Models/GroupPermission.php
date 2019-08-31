<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupPermission extends Model
{
    protected $table = 'group_permission';

    public function getRole()
    {
      return $this->permission_level;
    }

    public function isAdmin()
    {
        return (!empty($this->getRole()) && $this->getRole() == 'Administrator');
    }

    public function isMod()
    {
      return (!empty($this->getRole()) && $this->getRole() == 'Moderator');
    }

    public function tag()
    {
      $str = "";
      if($this->isAdmin() || $this->isMod()) {
        $title = trans('label.role.'. mb_strtolower($this->permission_level, 'utf-8'));
        $str = "<i class='fa fa-user-shield' title='". $title ."'></i>";
      }
      return $str;
    }

}
