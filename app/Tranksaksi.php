<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tranksaksi extends Model
{
    public function barang()
    {
      return $this->belongsTo('App\Barang');
    }

    public function member()
    {
      return $this->belongsTo('App\Member');
    }

    public function scopeMember($query)
    {
      return $query->where('member_id', 1);
    }
}
