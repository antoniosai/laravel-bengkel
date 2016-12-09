<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    public function tranksaksi()
    {
      return $this->hasMany('App\Tranksaksi');
    }

}
