<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Barang;
use App\BarangMasuk;

use App\Order;
use App\OrderTemp;
use App\Tranksaksi;

use Excel; 

class BarangController extends Controller
{
    //Api
    public function apiAllBarang()
    {
      $barang = Barang::select('harga', 'harga_jual', 'harga_khusus', 'nama_barang', 'stok', 'opsi_tukarpoin')->get();
      return response()->json($barang);
    }

    public function apiSearchBarang($nama)
    {
      return $barang = Barang::where('nama_barang', 'LIKE', '%'.$nama.'%')->get();
    }

    public function getAddBarang()
    {
      $barang = Barang::select('id', 'harga', 'harga_jual', 'harga_khusus', 'nama_barang', 'bobot_poin' ,'stok', 'opsi_tukarpoin')->get();

      return view('backend.barang', [
        'barang' => $barang
      ]);
    }

    public function importBarang(Request $request)
    {
      $importExcel = Excel::load($request->file('excel'), function($reader){
        $reader->each(function($sheet){
          Barang::create($sheet->toArray());
        });
      });

      // return $request->all();
      if ($importExcel) {
        $request->session()->flash('success', 'Berhasil mengimport barang dari file Excel');
        return redirect()->back();
      }
    }

    public function generateExcelTemplate()
    {

      Excel::create('Template Import Buku', function($excel) {
      // Set the properties
        $excel->setTitle('Template Import Barang')
          ->setCreator('Area Motor')
          ->setCompany('Area Motor')
          ->setDescription('Import Barang');
          $excel->sheet('Data Barang', function($sheet) {
            $row = 1;
            $sheet->row($row, [
            'nama_barang',
            'stok',
            'harga',
            'harga_jual',
            'harga_khusus',
            'bobot_poin'
          ]);
        });
      })->export('xlsx');
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
      $this->checkItemOrderTemp($id);

      $barang = Barang::findOrFail($id);
      $barang->delete();

      if ($barang) {
        return redirect()->back()->with('successMessage', 'Barang berhasil dihapus');
      } else {
        return redirect()->back()->with('errorMessage', 'Barang tidak bisa dihapus');
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

    private function checkItemOrderTemp($barang_id)
    {
      $barang = Barang::findOrFail($barang_id);

      if (Order::where('barang_id', $barang->id)->first()) {

        return redirect()->back()->with('errorMessage', 'Barang Tidak Bisa Dihapus Karena Sedang Di Order');
      }

      if (Tranksaksi::where('barang_id', $barang->id)->first()) {

        return redirect()->back()->with('errorMessage', 'Barang Tidak Bisa Dihapus Karena Sedang Di Order');
      }

    }

}
