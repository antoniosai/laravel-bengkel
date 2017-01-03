<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\BarangMasuk;
use App\LabaRugi;
use App\TukarPoin;

use App\Http\Controllers\LibraryController as Library;

use PDF;

class ExportController extends Controller
{
    public function __construct()
    {
      $this->listBulan = Library::generateBulanIndo();
      $this->listTahun = Library::generateTahunIndo();
    }

    public function barangMasukToPdf()
    {

      $barangMasukQuery = "
        SELECT barangs.nama_barang, barang_masuks.stok_masuk, barang_masuks.created_at, barang_masuks.user_id
        FROM barangs, barang_masuks, users
        WHERE barang_masuks.barang_id = barangs.id
        AND barang_masuks.user_id = users.id
      ";

      $barangMasuk = DB::select(DB::raw($barangMasukQuery));

      $pdf = PDF::loadView('export.barangmasuk', [
        'barangMasuk' => $barangMasuk
      ]);

      $randomString = date('d').'-'.date('m').'-'.date('y');

      $namaFile = 'barang-masuk-'.$randomString.'.pdf';

      // return $pdf->download($namaFile);
      return $pdf->stream();

    }

    public static function labaRugiToPdf($bulan, $tahun)
    {
      $labaRugi = LabaRugi::whereYear('created_at', $tahun)
                          ->whereMonth('created_at', $bulan)
                          ->get();

      $pdf = PDF::loadView('export.labarugi', [
        'labarugi' => $labaRugi,
        'listBulan' => Library::generateBulanIndo(),
        'bulan' => $bulan,
        'tahun' => $tahun
      ]);

      $randomString = date('d').'-'.date('m').'-'.date('y');

      $namaFile = 'laba-rugi-'.$randomString.'.pdf';

      // return $pdf->download($namaFile);
      return $pdf->stream();
    }

    public static function labaRugiDetailToPdf($date)
    {
      $penjualanQuery = "
        SELECT tranksaksi_temps.created_at, tranksaksi_temps.nota_id, tranksaksi_temps.faktur_id, users.name, members.nama_member, tranksaksi_temps.total, tranksaksi_temps.modal, tranksaksi_temps.laba, members.nama_member, users.name
        FROM tranksaksi_temps, members, users
        WHERE tranksaksi_temps.member_id = members.id
        AND tranksaksi_temps.user_id = users.id
        AND tranksaksi_temps.created_at LIKE '%$date%'
        ORDER BY tranksaksi_temps.created_at DESC
      ";

      // $laba_rugi = DB::select(DB::raw($labaRugiQuery));
      $penjualan = DB::select(DB::raw($penjualanQuery));

      $pdf = PDF::loadView('export.labarugidetail', [
        'penjualan' => $penjualan,
        'listBulan' => Library::generateBulanIndo(),
        'date' => $date
      ]);

      $pdf->setPaper('A4', 'landscape');

      $randomString = date('d').'-'.date('m').'-'.date('y');

      $namaFile = 'laba-rugi-'.$randomString.'.pdf';

      return $pdf->download($namaFile);
      // return $pdf->stream();
    }

    public static function penukaranPoinToPdf($bulan, $tahun)
    {
      $query = "
        SELECT hadiahs.nama_barang, members.nama_member, users.name, tukar_poins.created_at
        FROM hadiahs, members, users, tukar_poins
        WHERE tukar_poins.barang_id = hadiahs.id
        AND tukar_poins.member_id = members.id
        AND tukar_poins.user_id = users.id
      ";

      if ($bulan) {
        $query .= "
          AND month(tukar_poins.created_at) = $bulan
        ";
      }

      if ($tahun) {
        $query .= "
          AND year(tukar_poins.created_at) = $tahun
        ";
      }

      $query .= 'ORDER BY tukar_poins.created_at DESC';

      $penukaranPoin = DB::select(DB::raw($query));

      $pdf = PDF::loadView('export.tukarpoin', [
        'tukarpoin' => $penukaranPoin,
        'listBulan' => Library::generateBulanIndo(),
        'bulan' => $bulan,
        'tahun' => $tahun
      ]);

      $randomString = date('d').'-'.date('m').'-'.date('y');

      $namaFile = 'penukaran-poin'.$randomString.'.pdf';

      // return $pdf->download($namaFile);
      return $pdf->stream();
    }

    public static function penjualanToPdf($bulan, $tahun)
    {
      $queryTranksaksi = "
        SELECT tranksaksi_temps.created_at, tranksaksi_temps.nota_id, tranksaksi_temps.faktur_id, users.name, members.nama_member, tranksaksi_temps.total, tranksaksi_temps.subtotal, tranksaksi_temps.diskon
        FROM tranksaksi_temps, members, users
        WHERE tranksaksi_temps.member_id = members.id
        AND tranksaksi_temps.user_id = users.id
      ";

      if ($bulan) {
        $queryTranksaksi .= "
          AND month(tranksaksi_temps.created_at) = $bulan
        ";
      }

      if ($tahun) {
        $queryTranksaksi .= "
          AND year(tranksaksi_temps.created_at) = $tahun
        ";
      }

      $queryTranksaksi .= 'ORDER BY tranksaksi_temps.created_at DESC';

      $penjualan = DB::select(DB::raw($queryTranksaksi));

      $pdf = PDF::loadView('export.penjualan', [
        'penjualan' => $penjualan,
        'listBulan' => Library::generateBulanIndo(),
        'bulan' => $bulan,
        'tahun' => $tahun
      ]);

      $randomString = date('d').'-'.date('m').'-'.date('y');

      $namaFile = 'penjualan'.$randomString.'.pdf';

      // return $pdf->download($namaFile);
      return $pdf->stream();


    }

    public function fakturToPdf($nota)
    {
      $fakturQuery = "
        SELECT tranksaksi_temps.created_at, tranksaksi_temps.nota_id, tranksaksi_temps.faktur_id, users.name, members.nama_member, tranksaksi_temps.total, tranksaksi_temps.subtotal
        FROM tranksaksi_temps, members, users
        WHERE tranksaksi_temps.member_id = members.id
        AND tranksaksi_temps.user_id = users.id
        AND tranksaksi_temps.nota_id = $nota
      ";

      $tranksaksiQuery = "
        SELECT tranksaksis.created_at, tranksaksis.nota_id ,barangs.nama_barang, barangs.harga_jual as harga ,tranksaksis.qty, tranksaksis.total, tranksaksis.diskon, tranksaksis.subtotal
        FROM barangs, tranksaksis, members
        WHERE tranksaksis.nota_id = $nota
        AND tranksaksis.member_id = members.id
        AND tranksaksis.barang_id = barangs.id
        ORDER BY tranksaksis.created_at DESC
      ";

      $faktur = DB::select(DB::raw($fakturQuery));
      $tranksaksi = DB::select(DB::raw($tranksaksiQuery));

      $pdf = PDF::loadView('export.faktur', [
        'faktur' => $faktur,
        'tranksaksi' => $tranksaksi
      ]);

      $randomString = date('d').'-'.date('m').'-'.date('y');

      $namaFile = 'faktur'.$randomString.'.pdf';

      // return $pdf->download($namaFile);
      return $pdf->stream();
    }

}
