<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Diskon;
use App\Poin;

class TestController extends Controller
{
  public static function setDiskon($belanja)
  {
    $diskon = Diskon::all()->sortByDesc('harga_belanja');

    foreach ($diskon as $diskonList) {
      if ($belanja > $diskonList->harga_belanja) {
        $diskon = $diskonList->diskon;
        return $diskon;
        // return $this->grandTotal = $this->grandTotal - $diskon;
      }
    }
  }

  public function test($id)
  {
    return $id;
  }

}
