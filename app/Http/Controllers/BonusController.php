<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Poin;
use App\Diskon;

class BonusController extends Controller
{
    public function index()
    {
      $poin = Poin::all()->sortBy('harga_belanja');
      $diskon = Diskon::all()->sortBy('harga_belanja');

      return view('backend.bonus',[
        'poin' => $poin,
        'diskon' => $diskon
      ]);
    }

    public function postAddDiskon(Request $request)
    {
      $diskon = new Diskon;
      $diskon->harga_belanja = $request->input('harga_belanja');
      $diskon->diskon = $request->input('diskon');
      $diskon->save();

      if ($diskon) {
        $request->session()->flash('alert-success', 'Diskon berhasil ditambahkan');
        return redirect()->back();
      };
    }

    public function postAddPoin(Request $request)
    {
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
}
