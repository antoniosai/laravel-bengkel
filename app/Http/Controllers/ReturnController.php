<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Returns;
use App\TranksaksiTemp;
use App\Tranksaksi;
use App\Barang;
use App\LabaRugi;
use App\Member;
use App\User;
use App\BarangMasuk;

use App\Http\Controllers\LibraryController as Library;

use Auth;

use DB;

class ReturnController extends Controller
{
    public function index()
    {
        $today = date('d');

    	$queryTranksaksi = "
    		SELECT tranksaksi_temps.created_at, tranksaksi_temps.nota_id, tranksaksi_temps.faktur_id, users.name, members.nama_member, tranksaksi_temps.total, tranksaksi_temps.subtotal, tranksaksi_temps.diskon
        	FROM tranksaksi_temps, members, users
        	WHERE tranksaksi_temps.member_id = members.id
        	AND tranksaksi_temps.user_id = users.id            
            AND day(tranksaksi_temps.created_at) = $today
            ORDER BY tranksaksi_temps.created_at DESC
      	";


      	$tranksaksi = DB::select(DB::raw($queryTranksaksi));

      	return view('backend.return', [
      		'tranksaksi' => $tranksaksi
      	]);

    }

    public function detail($nota_id)
    {
        return $tranksaksi = Tranksaksi::where('nota_id', $nota_id)->get();
    }

    public function returns(Request $request)
    {
        // return $request->all();

        $barang_id = $request->input('barang_id');
        $member_id = $request->input('member_id');
        $user_id = $request->input('user_id');
        $tranksaksi_id = $request->input('tranksaksi_id');
        $nota_id = $request->input('nota_id');
        $qty = $request->input('qty');

        $barang = Barang::findOrFail($barang_id);
        $user = User::findOrFail($user_id);
        $member = Member::findOrFail($member_id);
        $tranksaksi = Tranksaksi::findOrFail($tranksaksi_id);
        $ket_tranksaksai = TranksaksiTemp::where('nota_id', $nota_id)->first();

        $tranksaksiAll = Tranksaksi::where('nota_id', $nota_id)->get();

        $tranksaksiQty = $tranksaksi->qty - $qty;

        if ($tranksaksiQty < 0) {
            $request->session()->flash('alert-danger', 'Opps! Qty barang tidak boleh kurang dari 0');
            return redirect()->back();
        }

        if ($qty > $tranksaksi->qty) {
            $request->session()->flash('alert-danger', 'Opps! Qty barang tidak boleh lebih dari '.$tranksaksi->qry);
            return redirect()->back();
        }

        //Tambah Stok dan Barang Masuk
        $barang->stok = $barang->stok + $qty;
        $barang->save();

        $barangMasuk = new BarangMasuk;
        $barangMasuk->user_id = $user_id;
        $barangMasuk->barang_id = $barang_id;
        $barangMasuk->stok_masuk = $qty;
        $barangMasuk->detail = 'Return';
        $barangMasuk->save();

        //Buat data ke table returns
        $pengembalian = new Returns;
        $pengembalian->user_id = $user_id;
        $pengembalian->barang_id = $barang_id;
        $pengembalian->member_id = $member_id;
        $pengembalian->qty = $qty;
        $pengembalian->alasan = $request->input('alasan');
        $pengembalian->save();

        //Update Laba Rugi
        $tanggalTranksaksi = Library::timeStampToDate($ket_tranksaksai->created_at);

        //Hitung Modal Omset Laba
        $modal = $tranksaksi->harga_pokok * $qty;
        $omset = $tranksaksi->harga_asli * $qty;
        $laba = $omset - $modal;

        $labaRugi = LabaRugi::where('created_at', 'LIKE', '%'.$tanggalTranksaksi.'%')->first();
        $labaRugi->omset = $labaRugi->omset - $omset;
        $labaRugi->laba = $labaRugi->laba - $laba;
        $labaRugi->modal = $labaRugi->modal - $modal;
        $labaRugi->save();

        //Update data keterangan Tranksaksi
        $ket_tranksaksai->total = $ket_tranksaksai->total - $omset;
        $ket_tranksaksai->laba = $ket_tranksaksai->laba - $laba;
        $ket_tranksaksai->modal = $ket_tranksaksai->modal - $modal;
        $ket_tranksaksai->subtotal = $ket_tranksaksai->total;
        $ket_tranksaksai->save();

        //Update Tranksaksi Table, perubahan Qty
        $totalQty = $tranksaksi->qty - $qty;

        if ($totalQty == 0) {
            $tranksaksi->delete();

            if (count($tranksaksiAll) == 0) {
                $request->session()->flash('alert-success', 'Berhasil mereturn '. $barang->nama_barang);
                return redirect()->to('admin/return');
            }

        } else {
            $tranksaksi->total = $tranksaksi->total - ($tranksaksi->harga_asli * $qty);
            $tranksaksi->qty = $tranksaksi->qty - $qty;
            $tranksaksi->save();
        }
        //

        //Tambah Stok
        $request->session()->flash('alert-success', 'Berhasil mereturn '. $barang->nama_barang);
        return redirect()->back();


    }
}
