<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Tema;
use App\Toko;

class TokoController extends Controller
{
    public function index()
    {
      $tema = Tema::all();
      $toko = Toko::all()->first();

      return view('backend.setting', [
        'tema' => $tema,
        'toko' => $toko
      ]);
    }

    public function postEditToko(Request $request)
    {
      $toko = Toko::findOrFail($request->input('id'));
      $toko->nama_toko = $request->input('nama_toko');
      $toko->telepon = $request->input('telepon');
      $toko->email = $request->input('email');
      $toko->alamat = $request->input('alamat');
      $toko->save();

      if ($toko) {
        $request->session()->flash('alert-success', 'Informasi Toko Berhail Diupdate');
        return redirect()->back();
      }
    }

    public function applyTheme(Request $request)
    {
      $toko = Toko::all()->first();

      if ($toko->tema == $request->input('tema')) {
        $request->session()->flash('alert-warning','Tema ini sedang digunakan');
        return redirect()->back();
      }

      $toko->tema = $request->input('tema');
      $toko->save();

      if ($toko) {
        $request->session()->flash('alert-success','Tema telah berhasil diganti');
        return redirect()->back();
      }
    }
}