<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Member;
use App\Barang;

class ReportController extends Controller
{
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

    public function labaRugi($bulan = null, $tahun = null)
    {
      $labaRugiQuery = "
        SELECT sum()
      ";

      return view('backend.report.labarugi');
    }

    public function sales()
    {

      $queryTranksaksi = "
        SELECT tranksaksi_temps.created_at, tranksaksi_temps.nota_id ,members.nama_member, tranksaksi_temps.total, tranksaksi_temps.subtotal, tranksaksi_temps.diskon
        FROM tranksaksi_temps, members
        WHERE tranksaksi_temps.member_id = members.id
      ";

      $queryTukarPoin = "
        SELECT tukar_poins.created_at, members.nama_member, barangs.nama_barang, users.name
        FROM tukar_poins, members, barangs, users
        WHERE tukar_poins.member_id = members.id
        AND tukar_poins.user_id = users.id
        AND tukar_poins.barang_id = barangs.id
      ";
      $tukarPoin = DB::select(DB::raw($queryTukarPoin));
      $tranksaksi = DB::select(DB::raw($queryTranksaksi));

      return view('backend.report.sales', [
        'tukar_poin' => $tukarPoin,
        'tranksaksi' => $tranksaksi
      ]);
    }

    public function barang()
    {
      $barangMasuk = [];
      $barang = [];
      $tanggal = [];

      $barangMasukQuery = "
        SELECT barangs.nama_barang, barang_masuks.stok_masuk, barang_masuks.created_at
        FROM barangs, barang_masuks
        ORDER BY barang_masuks.created_at DESC
      ";

      $barangMasuks = DB::select(DB::raw($barangMasukQuery));

      foreach ($barangMasuks as $barangs) {
        array_push($barang, $barangs->nama_barang);
        array_push($barangMasuk, $barangs->stok_masuk);
        array_push($tanggal, $barangs->created_at);
      }

      $barangEloquent = Barang::all();

      $barangKeluarQuery = "
        SELECT barangs.nama_barang, members.nama_member, barang_keluars.stok_keluar, barang_keluars.tranksaksi ,barang_keluars.created_at
        FROM barangs, barang_keluars, members
        WHERE barang_keluars.barang_id = barangs.id
        AND barang_keluars.member_id = members.id
        ORDER BY barang_keluars.created_at DESC
      ";

      $barangKeluar = DB::select(DB::raw($barangKeluarQuery));

      return view('backend.report.barang',[
        'barangMasuk' => $barangMasuks,
        'barangKeluar' => $barangKeluar,
        'barang' => $barang,
        'tanggal' => $tanggal,
        'barang_eloquent' => $barangEloquent
      ]);
    }

    public function user()
    {

    }
}
