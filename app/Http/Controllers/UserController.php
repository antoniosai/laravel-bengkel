<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\User;

class UserController extends Controller
{
  public function apiUser()
  {
    $user = User::all();

    return response()->json($user);
  }

  public function index()
  {

    $user = User::where('id', '!=', Auth::user()->id)->get();

    // $user = User::all();

    return view('backend.user', [
      'user' => $user,
    ]);
  }

  public function postAddUser(Request $request)
  {

    // return $request->all();

    if ($request->input('password') != $request->input('password_confirmation')) {
      $request->session()->flash('alert-danger', 'Password dan Password Confirmation tidak sama');
      return redirect()->back()->withInput();
    } 
    else if (count($request->input('hak_akses')) == 0) {
      $request->session()->flash('alert-danger', 'Pilih minimal satu hak akses');
      return redirect()->back()->withInput();
    } 
    else 
    {
      $messages = [
        'name.required' => 'Nama user harus diisi',
        'username.required' => 'Username harus diisi',
        'username.unique' => 'Username tersebut sudah digunakan',
        'email.required' => 'Email harus diisi',
        'email.unique' => 'Email tersebut sudah digunakan',
      ];

      $rules = [
        'name' => 'required',
        'username' => 'required|unique:users',
        'email' => 'required|unique:users',
      ];

      $this->validate($request, $rules, $messages);

      $user = new User;
      $user->name = $request->input('name');
      $user->username = $request->input('username');
      $user->email = $request->input('email');
      $user->password = bcrypt($request->input('password'));
      $user->save();

      $user->assignRole('admin');

      $akses = [];

      foreach ($request->input('hak_akses') as $permission) {
        array_push($akses, $permission);
      }

      $user->givePermissionTo($akses);

      if ($user) {
        $request->session()->flash('alert-success', 'User baru berhasil ditambahkan');
        return redirect()->back();
      }
    }
  }

  public function deleteUser($id)
  {
    $user = User::find($id);
    try {
       if ($user->delete()) {
        return redirect()->back()->with('successMessage', 'Barang berhasil dihapus');
      }
    } catch (\Illuminate\Database\QueryException $e) {
      return redirect()->back()->with('errorMessage', 'User '.$user->name.' tidak bisa dihapus karena sedang ada di dalam daftar Order');
    }

  }

  public function keluar(Request $request)
  {
    Auth::logout();

    return redirect()->to('login');
  }

  public function getProfile()
  {
    $user = User::findOrFail(Auth::user()->id);

    return view('backend.profile', [
      'user' => $user
    ]);
  }

  private function revokePermission($user_id)
  {
    $user = User::find($user_id);

    $aksesQuery = "
      SELECT permissions.name
      FROM permissions, users, user_has_permissions
      WHERE user_has_permissions.user_id = users.id
      AND user_has_permissions.permission_id = permissions.id                  
      AND user_has_permissions.user_id = $user_id
    ";

    $akses = DB::select(DB::raw($aksesQuery));

    foreach ($akses as $key) {
      $user->revokePermissionTo($key->name);
    }
  }

  private function givePermission($user_id, $data)
  {
    $user = User::find($user_id);

    $user->givePermissionTo($data);

  }

  public function postEditPermission(Request $request)
  {
    $messages = [
      'description.required' => 'Deskripsi hak akses harus diisi',
    ];

    $rules = [
      'description' => 'required'
    ];

    $this->validate($request, $rules, $messages);

    DB::table('permissions')->where('id', $request->input('id'))->update(['description' => $request->input('description')]);

    $request->session()->flash('alert-success', 'Hak akses berhasil diupdate');
    return redirect()->back();
  }

  public function postEditUser(Request $request)
  {

    $id = $request->input('id');

    $user = User::findOrFail($id);

    if (count($request->input('hak_akses')) == 0) {
      $request->session()->flash('alert-danger', 'Pilih minimal satu hak akses');
      return redirect()->back()->withInput();
    }

    if ($request->input('password_confirmation') == "" && $request->input('password') == "") {
      $user->name = $request->input('name');
      $user->username = $request->input('username');
      $user->email = $request->input('email');
      $user->save();

      $this->revokePermission($id);
      $this->givePermission($id, $request->input('hak_akses'));

      if ($user) {
        $request->session()->flash('alert-success', 'Profile berhasil diganti');
        return redirect()->back()->with('successMessage,', 'Profile berhasil diganti');
      }
    } else {
      if ($request->input('password-confirm') != $request->input('password')){
        $request->session()->flash('alert-danger', 'Komfirmasi password tidak sama');
        return redirect()->back()->withInput();
      } else {
        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();

        $this->revokePermission($id);
        $this->givePermission($id, $request->input('hak_akses'));

        if ($user) {
          $request->session()->flash('alert-success', 'Profile berhasil diganti');
          return redirect()->back()->with('successMessage,', 'Profile berhasil diganti');
        }
      }
    }
    
   
  }
}
