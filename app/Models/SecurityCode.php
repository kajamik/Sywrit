<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityCode extends Model
{
    protected $table = 'security_code';

    public function setUpdatedAt($value)
    {
        return $this;
    }


}
