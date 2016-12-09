<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Member;
use App\Diskon;

class Order extends Model
{

  public $totalBelanja;
  public $grandTotal;
  public $idMember;
  public $diskon;

  public function barang()
  {
    return $this->belongsTo('App\Barang');
  }

  public static function setDiskon($belanja)
  {
    $diskon = Diskon::orderBy('harga_belanja', 'ASC')->get();

    foreach ($diskon as $diskonList) {
      if ($belanja > $diskonList->harga_belanja) {
        $diskon = $diskonList->diskon;
        // return $this->diskon = $diskon;
        return $diskon;
        // return $this->grandTotal = $this->grandTotal - $diskon;
      }
    }
    return 0;
  }

  public function setNota($nota)
  {
    return $this->nota = $nota;
  }

  public static function getNota()
  {
    return $this->nota;
  }

}
