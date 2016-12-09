<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Barang;
use App\BarangMasuk;

class BarangController extends Controller
{
    //Api
    public function apiAllBarang()
    {
      $barang = Barang::all();
      return response()->json($barang);
    }

    public function apiSearchBarang($nama)
    {
      return $barang = Barang::where('nama_barang', 'LIKE', '%'.$nama.'%')->get();
    }

    public function getAddBarang()
    {
      $barang = Barang::all();

      return view('backend.barang', [
        'barang' => $barang
      ]);
    }

    public function importBarang(Request $request)
    {
      return $request->all();
    }

    public function postAddBarang(Request $request)
    {
      // return $request->all();

      if ($request->input('harga_jual') < $request->input('harga')) {
        $request->session()->flash('alert-warning', 'Harga jual tidak boleh lebih rendah dari harga beli');
        return redirect()->back()->withInput();
      }

      $barang = new Barang;
      $barang->nama_barang = $request->input('nama_barang');
      $barang->harga = $request->input('harga');
      $barang->harga_jual = $request->input('harga_jual');
      $barang->stok = $request->input('stok');

      if (!$request->input('harga_khusus') == "") {
        $barang->harga_khusus = $request->input('harga_khusus');
      }

      if (!$request->input('bobot_poin') == "") {
        $barang->bobot_poin = $request->input('bobot_poin');
      }

      $barang->save();

      if ($barang) {
        $request->session()->flash('alert-success', 'Barang baru berhasil ditambahkan');
        return redirect()->back();
      }
    }

    public function postEditBarang(Request $request)
    {
      $id = $request->input('id');

      $barang = Barang::findOrFail($id);
      $barang->nama_barang = $request->input('nama_barang');
      $barang->harga = $request->input('harga');
      $barang->harga_jual = $request->input('harga_jual');
      $barang->stok = $request->input('stok');
      $barang->bobot_poin = $request->input('bobot_poin');
      $barang->save();

      if ($barang) {
        $request->session()->flash('alert-success', 'Barang baru berhasil diedit');
        return redirect()->back();
      }
    }

    public function deleteBarang($id)
    {
      $barang = Barang::findOrFail($id);
      $barang->delete();

      if ($barang) {
        return redirect()->back()->with('successMessage', 'Barang berhasil dihapus');
      }
    }

    public function tambahStok(Request $request)
    {
      $id = $request->input('id');
      $barang = Barang::find($id);
      $barang->stok = $barang->stok + $request->input('stok_tambahan');
      $barang->save();

      $barangMasuk = new BarangMasuk;
      $barangMasuk->barang_id = $request->input('id');
      $barangMasuk->stok_masuk = $request->input('stok_tambahan');
      $barangMasuk->save();

      if ($barang) {
        $request->session()->flash('alert-success', 'Stok berhasil ditambahkan');
        return redirect()->back();
      }
    }

}
