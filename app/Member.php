<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    public function tranksaksi()
    {
      return $this->hasMany('App\Tranksaksi');
    }

    public function tukarpoin()
    {
      return $this->hasMany('App\TukarPoin');
    }

}
