<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Tema;
use App\Toko;
use App\User;

use Auth;
use DB;

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
      $messages = [
        'nama_toko.required' => 'Nama Toko Tidak Boleh Kosong',
        'telepon.required' => 'Nomor Telepon Tidak Boleh Kosong',
        'email.required' => 'Email tidak boleh kosong',
        'email.email' => 'Email harus mengandung unsur @',
        'alamat.required' => 'Alamat tidak boleh kosong'
      ];

      $rules = [
        'nama_toko' => 'required',
        'telepon'      => 'required',
        'email'     => 'required',
        'alamat'  => 'required',
      ];

      $this->validate($request, $rules, $messages);

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

    public function applyTampilan(Request $request)
    {
      $toko = Toko::all()->first();


      $rules = [
        'login' => 'image|mimes:jpeg,bmp,png,jpg',
        'wallpaper' => 'image|mimes:jpeg,bmp,png,jpg',
        'logo' => 'image|mimes:jpeg,bmp,png,jpg'
      ];

      $this->validate($request, $rules);

      if ($request->file('logo')) {
        $file       = $request->file('logo');
        $fileName   = $file->getClientOriginalName();
        $request->file('logo')->move("images/logo", $fileName);

        $toko->logo = $fileName;
      }

      if ($request->file('login')) {
        $file       = $request->file('login');
        $fileName   = $file->getClientOriginalName();
        $request->file('login')->move("images/login", $fileName);

        $toko->halaman_login = $fileName;
      }

      if ($request->file('login_logo')) {
        $file       = $request->file('login_logo');
        $fileName   = $file->getClientOriginalName();
        $request->file('login_logo')->move("images/login", $fileName);

        $toko->login_logo = $fileName;
      }

      if ($request->file('wallpaper')) {
        $file       = $request->file('wallpaper');
        $fileName   = $file->getClientOriginalName();
        $request->file('wallpaper')->move("images/wallpaper", $fileName);

        $toko->wallpaper = $fileName;
      }

      $toko->save();

      if ($toko) {
        $request->session()->flash('alert-success', 'Berhasil mengganti tampilan');
        return redirect()->back();
      }
      
    }

    public function pemutihanData(Request $request)
    {
      // return $request->all();
      $password = $request->input('password');
      $password_confirmation = $request->input('password_confirmation');
      $laporan = $request->input('laporan');

      if ($password != $password_confirmation) {
        $request->session()->flash('alert-danger', 'Password konfirmasi tidak sama');
        return redirect()->back();
      }

      $user = User::where('username', 'admin')->first();

      $autentifikasi = Auth::attempt(['username' => $user->username, 'password' => $request->input('password')]);

      if ($autentifikasi) {

        $countLaporan = count($request->input('laporan'));

        for ($i=0; $i < $countLaporan; $i++) { 
          if ($laporan[$i] == 'tranksaksi') {
            DB::select(DB::raw('TRUNCATE tranksaksis'));
            DB::select(DB::raw('TRUNCATE tranksaksi_temps'));
          }
          if ($laporan[$i] == 'labarugi') {
            DB::select(DB::raw('TRUNCATE laba_rugis'));
          }
          if ($laporan[$i] == 'barangmasuk') {
            DB::select(DB::raw('TRUNCATE barang_masuks'));
          }
          if ($laporan[$i] == 'barangkeluar') {
            DB::select(DB::raw('TRUNCATE barang_keluars'));
          }
          if ($laporan[$i] == 'penukaranpoin') {
            DB::select(DB::raw('TRUNCATE tukar_poins'));
          }
          if ($laporan[$i] == 'returns') {
            DB::select(DB::raw('TRUNCATE returns'));
          }
        }

        $request->session()->flash('alert-success', 'Berhasil menghapus data laporan');
        return redirect()->back();
      } else {
        $request->session()->flash('alert-danger', 'Password dan Konfirmasi Password Tidak Sama');
        return redirect()->back();
      }

    }

}
