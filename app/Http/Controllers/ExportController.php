<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\BarangMasuk;

use App\Http\Controllers\LibraryController as Library;

use PDF;

class ExportController extends Controller
{
    public function barangMasukToPdf()
    {

      $barangMasukQuery = "
        SELECT barangs.nama_barang, barang_masuks.stok_masuk, barang_masuks.created_at
        FROM barangs, barang_masuks
        WHERE barang_masuks.barang_id = barangs.id
      ";

      $barangMasuk = DB::select(DB::raw($barangMasukQuery));

      $pdf = PDF::loadView('export.barangmasuk', [
        'barangMasuk' => $barangMasuk
      ]);

      $randomString = date('d').'-'.date('m').'-'.date('y');

      $namaFile = 'barang-masuk-'.$randomString.'.pdf';

      return $pdf->download($namaFile);
      // return $pdf->stream();

    }
}
