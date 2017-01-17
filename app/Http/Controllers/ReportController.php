<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Member;
use App\Barang;
use App\LabaRugi;
use App\User;

use App\Http\Controllers\LibraryController as Library;
use App\Http\Controllers\ExportController as Export;

class ReportController extends Controller
{
    private $listBulan;
    private $listTahun;
    private $tableLabaRugi;

    public function __construct()
    {
      $this->tableLabaRugi = 'laba_rugis';
      $this->listBulan = Library::generateBulanIndo();
      $this->listTahun = Library::generateTahunIndo();
    }


    public function member()
    {
      $member = [];
      $poin = [];

      foreach (Member::orderBy('poin', 'DESC')->take(6)->get() as $members) {
        array_push($member, $members->nama_member);
        array_push($poin, $members->poin);
      }

      $memberEloquent = Member::where('nama_member', '!=', 'Guest')->get();

      return view('backend.report.member',[
        'member' => $member,
        'poin' => $poin,
        'member_eloquent' => $memberEloquent
      ]);
    }

    public function memberById($id)
    {
      $queryTranksaksi = "
        SELECT tranksaksi_temps.created_at, tranksaksi_temps.nota_id, tranksaksi_temps.faktur_id, users.name, members.nama_member, tranksaksi_temps.total, tranksaksi_temps.subtotal, tranksaksi_temps.diskon
        FROM tranksaksi_temps, members, users
        WHERE tranksaksi_temps.member_id = members.id
        AND tranksaksi_temps.user_id = users.id
        AND tranksaksi_temps.member_id = $id
        ORDER BY tranksaksi_temps.created_at DESC
      ";

      $queryTukarPoin = "
        SELECT hadiahs.nama_barang, members.nama_member, users.name, tukar_poins.created_at, tukar_poins.poin
        FROM hadiahs, members, users, tukar_poins
        WHERE tukar_poins.barang_id = hadiahs.id
        AND tukar_poins.member_id = members.id
        AND tukar_poins.user_id = users.id
        AND tukar_poins.member_id = $id
        ORDER BY tukar_poins.created_at DESC
      ";

      $tukarpoin = DB::select(DB::raw($queryTukarPoin));

      $tranksaksi = DB::select(DB::raw($queryTranksaksi));


      $member = Member::findOrFail($id);

      return view('backend.report.memberbyid',[
        'member' => $member,
        'tranksaksi' => $tranksaksi,
        'tukarpoin' => $tukarpoin
      ]);
    }

    public function labaRugi($bulan = null, $tahun = null)
    {

      $labaRugiQuery = "
        SELECT *
        FROM $this->tableLabaRugi
        ORDER BY created_at DESC
      ";

      $labaRugi = DB::select(DB::raw($labaRugiQuery));

      return view('backend.report.labarugi',[
        'bulan' => $bulan,
        'tahun' => $tahun,
        'listBulan' => $this->listBulan,
        'listTahun' => $this->listTahun,
        'laba_rugi' => $labaRugi
      ]);

    }

    public function labaRugiByDate($bulan = null, $tahun = null)
    {
      // return Barang::where DB::raw('MONTH(created_at)', '=', date('m')))->get();

      $labaRugiQuery = "
        SELECT *
        FROM $this->tableLabaRugi  
      ";

      if ($bulan) {
        $labaRugiQuery .= "
          WHERE month(created_at) = $bulan
        ";
      }

      if ($tahun) {
        $labaRugiQuery .= "
          AND year(created_at) = $tahun
        ";
      }

      $labaRugi = DB::select(DB::raw($labaRugiQuery));

      return view('backend.report.labarugi',[
        'bulan' => $bulan,
        'tahun' => $tahun,
        'listBulan' => $this->listBulan,
        'listTahun' => $this->listTahun,
        'laba_rugi' => $labaRugi
      ]);
    }

    public function labaRugiSearch($query)
    {
      echo 'test';
    }

    public function labaRugiDetail($created_at)
    {
      $labaRugiQuery = "
        SELECT *
        FROM laba_rugis 
        WHERE created_at LIKE '%$created_at%';
        ORDER BY created_at DESC
      ";

      $penjualanQuery = "
        SELECT tranksaksi_temps.created_at, tranksaksi_temps.nota_id, tranksaksi_temps.faktur_id, users.name, members.nama_member, tranksaksi_temps.total, tranksaksi_temps.modal, tranksaksi_temps.laba, members.nama_member, users.name
        FROM tranksaksi_temps, members, users
        WHERE tranksaksi_temps.member_id = members.id
        AND tranksaksi_temps.user_id = users.id
        AND tranksaksi_temps.created_at LIKE '%$created_at%'
        ORDER BY tranksaksi_temps.created_at DESC
      ";

      // $laba_rugi = DB::select(DB::raw($labaRugiQuery));

      $laba_rugi = LabaRugi::where('created_at', 'LIKE', '%'.$created_at.'%')->first();
      $penjualan = DB::select(DB::raw($penjualanQuery));

      return view('backend.report.labarugidetail', [
        'laba_rugi' => $laba_rugi,
        'date' => $created_at,
        'penjualan' => $penjualan
      ]);
    }

    public function labaRugiBySingleDate($tanggal, $bulan, $tahun)
    {

    }

    public function postLabaRugi(Request $request)
    {
      $bulan = $request->input('bulan');
      $tahun = $request->input('tahun');

      switch ($request->input('report')) {
        case 'filter':
          $labaRugiQuery = "
            SELECT *
            FROM barangs
            WHERE month(created_at) = $bulan AND year(created_at) = $tahun
          ";

          return redirect()->to('admin/report/labarugi/'.$bulan.'/'.$tahun);  
        break;

        case 'export':
          return Export::labaRugiToPdf($bulan, $tahun);
        break;
      }
      
    }

    public function returns($bulan = null, $tahun = null)
    {
      $query = "
        SELECT members.nama_member, users.name, returns.qty, returns.alasan, returns.created_at, barangs.nama_barang
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

      $query .= 'ORDER BY returns.created_at DESC';

      $returns = DB::select(DB::raw($query));

      return view('backend.report.return', [
        'returns' => $returns,
        'listBulan' => $this->listBulan,
        'listTahun' => $this->listTahun,
        'bulan' => $bulan,
        'tahun' => $tahun,
      ]);
    }

    public function returnsByDate($bulan, $tahun)
    {
      return $this->returns($bulan, $tahun);
    }

    public function postReturns(Request $request)
    {
      $bulan = $request->input('bulan');
      $tahun = $request->input('tahun');

      switch ($request->input('report')) {
        case 'filter':
          return $this->returnsByDate($bulan, $tahun);
        break;

        case 'export':
          return Export::returnsToPdf($bulan, $tahun);
        break;
      }
    }

    public function sales($bulan = null, $tahun = null)
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

      $tranksaksi = DB::select(DB::raw($queryTranksaksi));

      return view('backend.report.sales', [
        'tranksaksi' => $tranksaksi,
        'listBulan' => $this->listBulan,
        'listTahun' => $this->listTahun,
        'bulan' => $bulan,
        'tahun' => $tahun
      ]);
    }

    public function salesByDate($bulan, $tahun)
    {
      return $this->sales($bulan, $tahun);
    }

    public function postSales(Request $request)
    {
      // return $request->all();

      $bulan = $request->input('bulan');
      $tahun = $request->input('tahun');

      switch ($request->input('report')) {
        case 'filter':
          return $this->salesByDate($bulan, $tahun);
        break;

        case 'pdf':
          return Export::penjualanToPdf($bulan, $tahun);
        break;
      }
    }

    public function barang($bulan = null, $tahun = null)
    {
      $barangMasuk = [];
      $barang = [];
      $tanggal = [];

      $barangMasukQuery = "
        SELECT barangs.nama_barang, barang_masuks.stok_masuk, barang_masuks.detail, barang_masuks.created_at
        FROM barangs, barang_masuks, users
        WHERE barang_masuks.barang_id = barangs.id
        AND barang_masuks.user_id = users.id
      ";

      $barangEloquent = Barang::all();

      $barangKeluarQuery = "
        SELECT barangs.nama_barang, members.nama_member, barang_keluars.stok_keluar, barang_keluars.tranksaksi ,barang_keluars.created_at
        FROM barangs, barang_keluars, members, users
        WHERE barang_keluars.barang_id = barangs.id
        AND barang_keluars.member_id = members.id
        AND barang_keluars.user_id = users.id
      ";

      if ($bulan) {
        $barangKeluarQuery .= " AND month(barang_keluars.created_at) = $bulan";
        $barangMasukQuery .= " AND month(barang_masuks.created_at) = $bulan";
      }

      if ($tahun) {
        $barangKeluarQuery .= " AND year(barang_keluars.created_at) = $tahun";
        $barangMasukQuery .= " AND year(barang_masuks.created_at) = $tahun";
      }

      $barangKeluarQuery .= ' ORDER BY barang_keluars.created_at DESC';
      $barangMasukQuery .= ' ORDER BY barang_masuks.created_at DESC';

      $barangKeluar = DB::select(DB::raw($barangKeluarQuery));
      $barangMasuks = DB::select(DB::raw($barangMasukQuery));

      foreach ($barangMasuks as $barangs) {
        array_push($barang, $barangs->nama_barang);
        array_push($barangMasuk, $barangs->stok_masuk);
        array_push($tanggal, $barangs->created_at);
      }

      return view('backend.report.barang',[
        'barangMasuk' => $barangMasuks,
        'barangKeluar' => $barangKeluar,
        'barang' => $barang,
        'tanggal' => $tanggal,
        'barang_eloquent' => $barangEloquent,
        'listBulan' => $this->listBulan,
        'listTahun' => $this->listTahun,
        'bulan' => $bulan,
        'tahun' => $tahun
      ]);
    }

    public function barangByDate($bulan, $tahun)
    {
      return $this->barang($bulan, $tahun);
    }

    public function postFilterBarang(Request $request)
    {

      $bulan = $request->input('bulan');
      $tahun = $request->input('tahun');

      switch ($request->input('report')) {
        case 'filter':
          return $this->barangByDate($bulan, $tahun);
        break;

        case 'export':
          if ($request->input('barang') == 'masuk') {
            return Export::barangMasukToPdf($bulan, $tahun);
          }
          if ($request->input('barang') == 'keluar') {
            return Export::barangKeluarToPdf($bulan, $tahun);
          }
        break;
      }

      
    }

    public function user()
    {
      $user = User::all();

      return view('backend.report.user',[
        'user' => $user
      ]);
    }

    public function userById($id)
    {
      $user = User::findOrFail($id);

      $queryPenjualan = "
        SELECT users.name, tranksaksi_temps.nota_id, members.nama_member, tranksaksi_temps.subtotal, tranksaksi_temps.total, tranksaksi_temps.created_at
        FROM users, members, tranksaksi_temps
        WHERE tranksaksi_temps.user_id = users.id
        AND tranksaksi_temps.member_id = members.id
        AND tranksaksi_temps.user_id = $id
        ORDER BY tranksaksi_temps.created_at DESC
      ";

      $queryPenukaranPoin = "
        SELECT hadiahs.nama_barang, members.nama_member, users.name, tukar_poins.created_at
        FROM hadiahs, members, users, tukar_poins
        WHERE tukar_poins.barang_id = hadiahs.id
        AND tukar_poins.member_id = members.id
        AND tukar_poins.user_id = users.id
        AND tukar_poins.user_id = $id
        ORDER BY tukar_poins.created_at DESC
      ";

      $queryBarangMasuk = "
        SELECT users.name, barangs.nama_barang, barang_masuks.detail, barang_masuks.stok_masuk, barang_masuks.created_at
        FROM users, barang_masuks, barangs
        WHERE barang_masuks.user_id = users.id
        AND barang_masuks.barang_id = barangs.id
        AND barang_masuks.user_id = $id
        ORDER BY barang_masuks.created_at DESC
      ";

      $penjualan = DB::select(DB::raw($queryPenjualan));
      $penukaranpoin = DB::select(DB::raw($queryPenukaranPoin));
      $barangmasuk = DB::select(DB::raw($queryBarangMasuk));

      return view('backend.report.userbyid', [
        'user' => $user,
        'penjualan' => $penjualan,
        'penukaran_poin' => $penukaranpoin, 
        'barang_masuk' => $barangmasuk
      ]);
    }

    public function penukaranPoin($bulan = null, $tahun = null)
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

      return view('backend.report.penukaranpoin', [
        'penukaran_poin' => $penukaranPoin,
        'listBulan' => $this->listBulan,
        'listTahun' => $this->listTahun,
        'bulan' => $bulan,
        'tahun' => $tahun,
      ]);
    }

    public function penukaranPoinByDate($bulan, $tahun)
    {
      return $this->penukaranPoin($bulan, $tahun);
    }

    public function postPenukaranPoin(Request $request)
    {
      $bulan = $request->input('bulan');
      $tahun = $request->input('tahun');

      switch ($request->input('report')) {
        case 'filter':
          return $this->penukaranPoinByDate($bulan, $tahun);
        break;

        case 'export':
          return Export::penukaranPoinToPdf($bulan, $tahun);
        break;
      }
    }
}
