<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{

	  protected $fillable = ['nama_barang', 'stok', 'harga', 'harga_jual', 'harga_khusus', 'bobot_poin', 'opsi_tukarpoin'];


	  public static function validator($data)
	  {
	  	$this->validate($data, $rules);
	  }

	  public function tranksaksi()
	  {
	    return $this->hasMany('App\Tranksaksi');
	  }

	  public function order()
	  {
	    return $this->hasMany('App\Order');
	  }

}
