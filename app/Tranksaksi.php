<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tranksaksi extends Model
{
    protected $fillable = ['user_id', 'nota_id', 'barang_id', 'qty', 'harga_asli', 'harga_pokok', 'harga_umum', 'total',  'status'];

    public function barang()
    {
      return $this->belongsTo('App\Barang');
    }

    public function member()
    {
      return $this->belongsTo('App\Member');
    }

    public function user()
    {
      return $this->belongsTo('App\User');
    }

    public function scopeMember($query)
    {
      return $query->where('member_id', 1);
    }
}
