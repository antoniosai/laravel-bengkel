<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\BarangMasuk;
use App\LabaRugi;
use App\TukarPoin;
use App\TranksaksiTemp;

use App\Http\Controllers\LibraryController as Library;

use PDF;

class ExportController extends Controller
{
    public function __construct()
    {
      $this->listBulan = Library::generateBulanIndo();
      $this->listTahun = Library::generateTahunIndo();
      // $this->stringBulan = ExportController::generateStringMonth($bulan);
    }

    public static function barangMasukToPdf($bulan, $tahun, $user_id = null)
    {

      $barangMasukQuery = "
        SELECT barangs.nama_barang, barang_masuks.stok_masuk, barang_masuks.created_at, barang_masuks.user_id
        FROM barangs, barang_masuks, users
        WHERE barang_masuks.barang_id = barangs.id
        AND barang_masuks.user_id = users.id
      ";
      

      if ($user_id) {
        $barangMasukQuery .= "
          AND barang_masuks.user_id = $user_id
        ";
      }

      $barangMasukQuery .= " AND month(barang_masuks.created_at) = $bulan";
      $barangMasukQuery .= " AND year(barang_masuks.created_at) = $tahun";
      $barangMasukQuery .= ' ORDER BY barang_masuks.created_at DESC';


      $barangMasuk = DB::select(DB::raw($barangMasukQuery));

      $pdf = PDF::loadView('export.barangmasuk', [
        'barangMasuk' => $barangMasuk,
        'bulan' => $bulan,
        'tahun' => $tahun,
        'listBulan' => Library::generateBulanIndo()
      ]);

      $stringBulan = self::generateStringMonth($bulan);

      $randomString = $stringBulan.' '.$tahun;

      $namaFile = 'Laporan Barang Masuk - Bulan '.$randomString. ' - ' . date('d m Y') .'.pdf';

      return $pdf->download($namaFile);

    }

    public static function barangKeluarToPdf($bulan, $tahun, $user_id = null)
    {

      $barangKeluarQuery = "
        SELECT barangs.nama_barang, members.nama_member, barang_keluars.stok_keluar, barang_keluars.tranksaksi ,barang_keluars.created_at
        FROM barangs, barang_keluars, members, users
        WHERE barang_keluars.barang_id = barangs.id
        AND barang_keluars.member_id = members.id
        AND barang_keluars.user_id = users.id
      ";

      $barangKeluarQuery .= " AND month(barang_keluars.created_at) = $bulan";

      $barangKeluarQuery .= " AND year(barang_keluars.created_at) = $tahun";

      if ($user_id) {
        $barangKeluarQuery .= " AND barang_keluars.user_id = $user_id";
      }

      $barangKeluarQuery .= ' ORDER BY barang_keluars.created_at DESC';

      $barangKeluar = DB::select(DB::raw($barangKeluarQuery));

      $pdf = PDF::loadView('export.barangkeluar', [
        'barangKeluar' => $barangKeluar,
        'bulan' => $bulan,
        'tahun' => $tahun,
        'listBulan' => LibraryController::generateBulanIndo()
      ]);

      $randomString = date('d').'-'.date('m').'-'.date('y');

      $namaFile = 'barang-keluar-'.$randomString.'.pdf';

      return $pdf->download($namaFile);

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

      $stringBulan = self::generateStringMonth($bulan);

      $randomString = $stringBulan.' '.$tahun;

      $namaFile = 'Laporan Laba Rugi - Bulan '.$randomString. ' - ' . date('d m Y') .'.pdf';

      return $pdf->download($namaFile);

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

    }

    public static function penukaranPoinToPdf($bulan, $tahun, $user_id)
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

      if ($user_id) {
        $query .= "
          AND tukar_poins.user_id = $user_id
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

      return $pdf->download($namaFile);

    }

    public static function returnsToPdf($bulan, $tahun, $user_id)
    {
      $query = "
        SELECT members.nama_member, users.name, returns.qty, returns.alasan, returns.created_at, barangs.nama_barang, returns.alasan
        FROM users, members, returns, barangs
        WHERE returns.barang_id = barangs.id
        AND returns.member_id = members.id
        AND returns.user_id = users.id
      ";

      if ($bulan) {
        $query .= "
          AND month(returns.created_at) = $bulan
        ";
      }

      if ($tahun) {
        $query .= "
          AND year(returns.created_at) = $tahun
        ";
      }

      if ($user_id) {
        $query .= "
          AND returns.user_id = $user_id
        ";
      }

      $query .= 'ORDER BY returns.created_at DESC';

      $returns = DB::select(DB::raw($query));

      $pdf = PDF::loadView('export.return', [
        'returns' => $returns,
        'listBulan' => Library::generateBulanIndo(),
        'bulan' => $bulan,
        'tahun' => $tahun
      ]);

      $randomString = date('d').'-'.date('m').'-'.date('y');

      $namaFile = 'penukaran-poin'.$randomString.'.pdf';

      return $pdf->download($namaFile);

    }

    public static function penjualanToPdf($bulan, $tahun)
    {
      $queryTranksaksi = "
        SELECT tranksaksi_temps.created_at, tranksaksi_temps.nota_id, tranksaksi_temps.faktur_id, users.name, members.nama_member, tranksaksi_temps.total, tranksaksi_temps.subtotal
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

      $stringBulan = self::generateStringMonth($bulan);

      $randomString = $stringBulan.' '.$tahun;

      $namaFile = 'Laporan Penjualan - Bulan '.$randomString. ' - ' . date('d m Y') .'.pdf';

      return $pdf->download($namaFile);

    }

    public function fakturToPdf($nota)
    {
      $faktur = TranksaksiTemp::where('nota_id', '=', $nota)->first();

      $tranksaksiQuery = "
        SELECT tranksaksis.created_at, tranksaksis.nota_id ,barangs.nama_barang, tranksaksis.harga_pokok, tranksaksis.harga_umum, tranksaksis.harga_khusus, tranksaksis.qty, tranksaksis.total, barangs.id as barang_id, members.id as member_id
        FROM barangs, tranksaksis, members
        WHERE tranksaksis.nota_id = $nota
        AND tranksaksis.member_id = members.id
        AND tranksaksis.barang_id = barangs.id
        ORDER BY tranksaksis.created_at DESC
      ";
      $tranksaksi = DB::select(DB::raw($tranksaksiQuery));

      $pdf = PDF::loadView('export.faktur', [
        'faktur' => $faktur,
        'tranksaksi' => $tranksaksi
      ]);

      $randomString = date('d').'-'.date('m').'-'.date('y');

      $namaFile = 'faktur'.$randomString.'.pdf';

      return $pdf->download($namaFile);
    }

    private static function generateStringMonth($month)
    {
      $listBulan = Library::generateBulanIndo();

      foreach($listBulan as $key => $value){
        if ($month == $key) {
          $stringBulan = $value;
        }
        // <option value="{{ $key }}">{{ $value }}</option>
      }

      return $stringBulan;
    }

}
