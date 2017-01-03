<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TranksaksiTemp extends Model
{
    public function users()
    {
    	return $this->belongsToMany('App\User', 'tranksaksi_temps', 'user_id');
    }

    public function members()
    {
    	return $this->belongsToMany('App/Member');
    }
}

