<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function traksaksis()
    {
        return $this->hasMany('App\TranksaksiTemp', 'tranksaksi_temps', 'user_id');
    }

    public function tranksaksi()
    {
        return $this->hasMany('App\Tranksaksi');
    }

    public function returns()
    {
        return $this->hasMany('App\Return');
    }

}
