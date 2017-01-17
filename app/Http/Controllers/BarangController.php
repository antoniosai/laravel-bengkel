<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Barang;
use App\BarangMasuk;

use App\Order;
use App\OrderTemp;
use App\Tranksaksi;
use App\User;
use App\Toko;

use Auth;

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
      $barang = Barang::select('id', 'harga', 'harga_jual', 'harga_grosir' ,'harga_khusus', 'nama_barang', 'bobot_poin' ,'stok', 'opsi_tukarpoin')->get();

      return view('backend.barang', [
        'barang' => $barang
      ]);
    }

    public function importBarang(Request $request)
    {
      $importExcel = Excel::load($request->file('excel'), function($reader){
        $reader->each(function($sheet){
          Barang::firstOrCreate($sheet->toArray());
          // return $sheet->toArray();
        });
      });

      try {
        if ($importExcel) {
          $request->session()->flash('success', 'Berhasil mengimport barang dari file Excel');
          return redirect()->back();
        }
      } catch (\Illuminate\Database\QueryException $e) {
        // $errorLevel = 'danger';
        // $message = 'Barang tidak bisa dihapus';
        // return redirect()->back()->with('errorMessage', $e->errorInfo[2]);
     
        var_dump($e->errorInfo);
      }

      // return $request->all();
      
    }

    public function generateExcelTemplate()
    {

      Excel::create('Template Import Barang', function($excel) {
      // Set the properties
        $excel->setTitle('Template Import Barang')
          ->setCreator(User::find(Auth::user()->id)->name)
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
            'harga_grosir',
            'opsi_tukarpoin'
          ]);
        });
      })->export('xlsx');
    }

    public function postAddBarang(Request $request)
    {
      // return $request->all();

      if ($request->input('harga_jual') < $request->input('harga')) {
        $request->session()->flash('alert-warning', 'Harga umum tidak boleh lebih rendah dari harga beli');
        return redirect()->back()->withInput();
      }

      if ($request->input('harga_grosir') < $request->input('harga')) {
        $request->session()->flash('alert-warning', 'Harga grosir tidak boleh lebih rendah dari harga beli');
        return redirect()->back()->withInput();
      }


      $rules = [
        'nama_barang' => 'required|unique:barangs',
        'stok'      => 'required|integer',
        'harga'     => 'required|integer',
        'harga_jual'  => 'required|integer',
        'harga_grosir' => 'required|integer'
      ];

      $messages = [
        'nama_barang.required' =>'Nama Barang harus diisi',
        'stok.required' => 'Stok harus disii',
        'stok.integer' => 'Stok harus berupa angka',
        'harga.required' => 'Harga harus diisi',
        'harga.integer' => 'Harga harus berupa nominal (angka)',
        'harga_jual.required' => 'Harga jual harus diisi',
        'harga_jual.integer' => 'Harga jual harus berupa nominal (angka)',
        'harga_khusus.integer' => 'Harga harus berupa nominal (angka)',
        'harga_grosir.required' => 'Harga grosir harus diisi',
        'harga_khusus.integer' => 'Harga grosir harus berupa nominal (angka)',
      ];

      $this->validate($request, $rules, $messages);

      $stringOpsiTukarBarang = '';

      if ($request->input('opsi_tukarpoin') == "") {
        $stringOpsiTukarBarang = 'no';
      } else {
        $stringOpsiTukarBarang = 'yes';
      }

      $barang = new Barang;
      $barang->nama_barang = $request->input('nama_barang');
      $barang->harga = $request->input('harga');
      $barang->harga_jual = $request->input('harga_jual');
      $barang->harga_grosir = $request->input('harga_grosir');
      $barang->stok = $request->input('stok');
      $barang->opsi_tukarpoin = $stringOpsiTukarBarang;

      if (!$request->input('harga_khusus') == "") {
        $barang->harga_khusus = $request->input('harga_khusus');
      }

      $barang->save();

      if ($barang) {
        $request->session()->flash('alert-success', 'Barang baru berhasil ditambahkan');
        return redirect()->back();
      }
    }

    public function postEditBarang(Request $request)
    {

      // return $request->all();

      $id = $request->input('id');

      $harga_khusus = null;

      if ($request->input('harga_khusus')  == '') {
        $harga_khusus = null;
      } else {
        $harga_khusus = $request->input('harga_khusus');
      }

      $rules = [
        'nama_barang' => 'required',
        'stok'      => 'required|integer',
        'harga'     => 'required|integer',
        'harga_jual'  => 'required|integer',
        'harga_khusus' => 'integer',
        'harga_grosir' => 'required|integer'
      ];

      $messages = [
        'nama_barang.required' =>'Nama Barang harus diisi',
        'stok.required' => 'Stok harus disii',
        'stok.integer' => 'Stok harus berupa angka',
        'harga.required' => 'Harga harus diisi',
        'harga.integer' => 'Harga harus berupa nominal (angka)',
        'harga_jual.required' => 'Harga jual harus diisi',
        'harga_jual.integer' => 'Harga jual harus berupa nominal (angka)',
        'harga_khusus.integer' => 'Harga harus berupa nominal (angka)',
        'harga_grosir.required' => 'Harga grosir harus diisi',
        'harga_grosir.integer' => 'Harga grosir harus berupa angka'
      ];

      $this->validate($request, $rules, $messages);

      $barang = Barang::findOrFail($id);
      $barang->nama_barang = $request->input('nama_barang');
      $barang->harga = $request->input('harga');
      $barang->harga_jual = $request->input('harga_jual');
      $barang->harga_khusus = $harga_khusus;
      $barang->harga_grosir = $request->input('harga_grosir');
      $barang->stok = $request->input('stok');
      $barang->opsi_tukarpoin = $request->input('opsi_tukarpoin');
      $barang->save();

      if ($barang) {
        $request->session()->flash('alert-success', 'Barang baru berhasil diedit');
        return redirect()->back();
      }
    }

    public function deleteBarang($id)
    {

      $barang = Barang::findOrFail($id);

      try {
         if ($barang->delete()) {
          return redirect()->back()->with('successMessage', 'Barang berhasil dihapus');
        }
      } catch (\Illuminate\Database\QueryException $e) {
        return redirect()->back()->with('errorMessage', 'Barang '.$barang->nama_barang.' tidak bisa dihapus karena sedang ada di dalam daftar Order');
      }

    }

    public function tambahStok(Request $request)
    {
      $id = $request->input('id');
      $barang = Barang::find($id);
      $barang->stok = $barang->stok + $request->input('stok_tambahan');
      $barang->save();

      $barangMasuk = new BarangMasuk;
      $barangMasuk->user_id = $request->input('user_id');
      $barangMasuk->barang_id = $request->input('id');
      $barangMasuk->stok_masuk = $request->input('stok_tambahan');
      $barangMasuk->detail = 'Update Stok';
      $barangMasuk->save();

      if ($barang) {
        $request->session()->flash('alert-success', 'Stok berhasil ditambahkan');
        return redirect()->back();
      }
    }

    protected function validator($data, $rules)
    {
      $this->validate($data, $rules);
    }

}
