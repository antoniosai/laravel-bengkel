<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Poin;
use App\Diskon;
use App\Hadiah;

class BonusController extends Controller
{
    public function index()
    {
      $poin = Poin::all()->sortBy('harga_belanja');
      $hadiah = Hadiah::all();
      return view('backend.bonus',[
        'poin' => $poin,
        'hadiah' => $hadiah
      ]);
    }

    public function postAddHadiah(Request $request)
    {
      $messages = [
        'nama_barang.required' => 'Nama barang harus diisi',
        'bobot_poin.required' => 'Bobot poin harus diisi',
        'bobot_poin.integer' => 'Bobot poin harus berupa angka',
        'stok.required' => 'Stok harus diisi',
        'stok.integer' => 'Stok harus berupa angka'
      ];

      $rules = [
        'nama_barang' => 'required|unique:hadiahs',
        'bobot_poin' => 'required|integer',
        'stok' => 'required|integer'
      ];

      $this->validate($request, $rules, $messages);

      $diskon = new Hadiah;
      $diskon->nama_barang = $request->input('nama_barang');
      $diskon->bobot_poin = $request->input('bobot_poin');
      $diskon->stok = $request->input('stok');
      $diskon->save();

      if ($diskon) {
        $request->session()->flash('alert-success', 'Diskon berhasil ditambahkan');
        return redirect()->back();
      };
    }

    public function postEditHadiah(Request $request)
    {
      $messages = [
        'nama_barang.required' => 'Nama barang harus diisi',
        'bobot_poin.required' => 'Bobot poin harus diisi',
        'bobot_poin.integer' => 'Bobot poin harus berupa angka',
        'stok.required' => 'Stok harus diisi',
        'stok.integer' => 'Stok harus berupa angka'
      ];

      $rules = [
        'nama_barang' => 'required',
        'bobot_poin' => 'required|integer',
        'stok' => 'required|integer'
      ];

      $this->validate($request, $rules, $messages);

      $id = $request->input('id');

      $diskon = Hadiah::findOrFail($id);
      $diskon->nama_barang = $request->input('nama_barang');
      $diskon->bobot_poin = $request->input('bobot_poin');
      $diskon->stok = $request->input('stok');
      $diskon->save();

      if ($diskon) {
        $request->session()->flash('alert-success', 'Berhasil mengupdat barang');
        return redirect()->back();
      };
    }

    public function postAddPoin(Request $request)
    {
      $rules = [
        'harga_belanja'      => 'required|integer',
        'poin'     => 'required|integer'
      ];

      $messages = [
        'harga_belanja.required' => 'Harga Belanja harus diisi',
        'poin.required' => 'Poin harus diisi',
        'harga_belanja.integer' => 'Harga Belanja harus berupa nominal (angka)',
        'poin.integer' => 'Poin harus berupa angka'
      ];

      $this->validate($request, $rules, $messages);

      $poin = new Poin;
      $poin->harga_belanja = $request->input('harga_belanja');
      $poin->poin = $request->input('poin');
      $poin->save();


      if ($poin) {
        $request->session()->flash('alert-success', 'Poin berhasil ditambahkan');
        return redirect()->back();
      }
    }

    public function postSaveDiskon(Request $request)
    {
      $id = $request->input('id');
      $diskon = Diskon::findOrFail($id);
      $diskon->harga_belanja = $request->input('harga_belanja');
      $diskon->diskon = $request->input('diskon');
      $diskon->save();

      if ($diskon) {
        $request->session()->flash('alert-success', 'Diskon telah berhasil di update');
        return redirect()->back();
      }
    }

    public function postSavePoin(Request $request)
    {

      $rules = [
        'harga_belanja'      => 'required|integer',
        'poin'     => 'required|integer'
      ];

      $messages = [
        'harga_belanja.required' => 'Harga Belanja harus diisi',
        'poin.required' => 'Poin harus diisi',
        'harga_belanja.integer' => 'Harga Belanja harus berupa nominal (angka)',
        'poin.integer' => 'Poin harus berupa angka'
      ];

      $this->validate($request, $rules, $messages);

      $id = $request->input('id');
      $poin = Poin::findOrFail($id);
      $poin->harga_belanja = $request->input('harga_belanja');
      $poin->poin = $request->input('poin');
      $poin->save();

      if ($poin) {
        $request->session()->flash('alert-success', 'Poin telah berhasil di update');
        return redirect()->back();
      }
    }

    public function deleteDiskon($id)
    {
      $diskon = Diskon::findOrFail($id);
      $diskon->delete();

      if ($diskon) {
        return redirect()->back()->with('successMessage', 'Diskon berhasil dihapus');
      }

    }

    public function deletePoin($id)
    {
      $poin = Poin::findOrFail($id);
      $poin->delete();

      if ($poin) {
        return redirect()->back()->with('successMessage', 'Poin berhasil dihapus');
      }

    }

    public function deleteHadiah($id)
    {
      $barang = Hadiah::findOrFail($id);

      try {
         if ($barang->delete()) {
          return redirect()->back()->with('successMessage', 'Barang berhasil dihapus');
        }
      } catch (\Illuminate\Database\QueryException $e) {
        return redirect()->back()->with('errorMessage', 'Barang '.$barang->nama_barang.' tidak bisa dihapus karena sedang ada di dalam daftar Order');
      }

    }
}
