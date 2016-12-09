<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\User;

use App\Role;

use App\Permission;

class UserController extends Controller
{
  public function apiUser()
  {
    $user = User::all();

    return response()->json($user);
  }

  public function index()
  {
    $userQuery = "
      SELECT users.id, users.name, users.username, users.email, roles.display_name
      FROM users, roles, role_user
      WHERE users.id = role_user.user_id
      AND roles.id = role_user.role_id
    ";

    $user = DB::select(DB::raw($userQuery));

    // $user = User::where('id', '!=', Auth::user()->id)->get();
    $role = Role::all();
    $permission = Permission::all();

    return view('backend.user', [
      'user' => $user,
      'role' => $role,
      'permission' => $permission
    ]);
  }

  public function postAddUser(Request $request)
  {
    // return $request->all();

    if ($request->input('password') != $request->input('password_confirmation')) {
      $request->session()->flash('alert-danger', 'Password dan Password Confirmation tidak sama');
      return redirect()->back();
    } else {

      $user = new User;
      $user->name = $request->input('name');
      $user->username = $request->input('username');
      $user->email = $request->input('email');
      $user->password = bcrypt($request->input('password'));
      $user->save();

      if ($user) {
        $request->session()->flash('alert-success', 'User baru berhasil ditambahkan');
        return redirect()->back();
      }
    }
  }

  public function keluar(Request $request)
  {
    Auth::logout();

    return redirect()->to('login');
  }
}
