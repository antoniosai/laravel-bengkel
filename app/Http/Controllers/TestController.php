<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Diskon;
use App\Poin;
use App\User;

class TestController extends Controller
{


  protected $passwordValidator;

  public static function setDiskon($belanja)
  {
    $diskon = Diskon::all()->sortByDesc('harga_belanja');

    foreach ($diskon as $diskonList) {
      if ($belanja > $diskonList->harga_belanja) {
        $diskon = $diskonList->diskon;
        return $diskon;
        // return $this->grandTotal = $this->grandTotal - $diskon;
      }
    }
  }

  public function test($id)
  {
    return $id;
  }

  public function cobaPost(Request $request)
  {
    // return $request->all();
    
    $user_id = $request->input('user_id');
    $password = $request->input('password');
    $password_repeat = $request->input('password_repeat');
    $user = User::findOrFail($user_id);

    // return decrypt($password);
    // return octdec(377);
    $credentials['password'] = $request->input('password');
    $credentials['password_confirmation'] = $request->input('password_repeat');

    return bcrypt($user->password);

    if ($user->password === bcrypt($password)) {
      return 'saama';
    } else {
      return 'beda';
    }

  }

}
