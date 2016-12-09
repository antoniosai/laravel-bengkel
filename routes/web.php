<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/


Auth::routes();

Route::get('home', 'PosController@index');

Route::get('keluar', 'UserController@keluar');

Route::group(['middleware' => 'auth'], function(){
  Route::get('/', 'PosController@dashboard');
});
Route::group(['middleware' => 'auth', 'prefix'=> 'admin'], function () {

  //=================== POS =====================//
  Route::group(['prefix' => 'pos'], function(){
    Route::get('/', 'PosController@index');
    Route::get('{nota}/{id}', [
      'as' => 'pos.idwithmember',
      'uses' => 'PosController@index'
    ]);
    Route::get('{nota}', [
      'as' => 'pos.id',
      'uses' => 'PosController@index'
    ]);

    Route::get('nota/{nota}/detail', [
      'as' => 'nota.detail',
      'uses' => 'PosController@detailNota'
    ]);
    Route::post('order', 'PosController@saveOrder');

    Route::get('order/delete/{id}', [
      'as' => 'order.delete',
      'uses' => 'PosController@deleteItem'
    ]);

    Route::get('order/nota/delete/{nota}', [
      'as' => 'nota.delete',
      'uses' => 'PosController@deleteOrder'
    ]);

    Route::post('save/tranksaksi', 'PosController@saveTranksaksi');

    Route::post('tukarpoin', 'PosController@tukarPoin');

    Route::post('order/update', 'PosController@updateQty');
    Route::post('setmember', 'PosController@applyMember');
    Route::post('unsetmember', 'PosController@unsetMember');
  });
  //=================== POS =====================//

  //=================== BARANG =====================//
  Route::group(['prefix' => 'barang'], function(){
    Route::get('/', 'BarangController@getAddBarang');
    Route::post('add', 'BarangController@postAddBarang');
    Route::post('edit', 'BarangController@postEditBarang');
    Route::post('add/stok', 'BarangController@tambahStok');
    Route::get('delete/{id}', [
      'as' => 'barang.delete',
      'uses' => 'BarangController@deleteBarang'
    ]);

    Route::post('import/barang', 'BarangController@importBarang');
  });
  //=================== BARANG =====================//


  //=================== MEMBER =====================//
  Route::group(['prefix' => 'member'], function(){
    Route::get('/', 'MemberController@index');
    Route::post('add', 'MemberController@postAddMember');
  });
  //=================== MEMBER =====================//

  //=================== REPORT =====================//
  Route::group(['prefix' => 'report'], function(){
    Route::get('/', 'ReportController@index');
    Route::get('sales', 'ReportController@sales');
    Route::get('member', 'ReportController@member');
    Route::get('barang', 'ReportController@barang');
    Route::get('user', 'ReportController@user');

    Route::get('labarugi', 'ReportController@labaRugi');
    // Route::get('labarugi/{bulan}/{tahun}', 'ReportController@labaRugi');

  });
  //=================== REPORT =====================//

  //=================== BONUS =====================//
  Route::group(['prefix' => 'bonus'], function(){
    Route::get('/', 'BonusController@index');
    Route::post('bonus/diskon/add', 'BonusController@postAddDiskon');
    Route::post('bonus/diskon/save', 'BonusController@postSaveDiskon');
    Route::post('bonus/poin/add', 'BonusController@postAddPoin');
    Route::post('bonus/poin/save', 'BonusController@postSavePoin');

    Route::get('delete/diskon/{id}', [
      'as' => 'delete.diskon',
      'uses' => 'BonusController@deleteDiskon'
    ]);
    Route::get('delete/poin/{id}', [
      'as' => 'delete.poin',
      'uses' => 'BonusController@deletePoin'
    ]);
  });
  //=================== BONUS =====================//

  //=================== USER =====================//
  Route::group(['prefix' => 'user'], function(){
    Route::get('/', 'UserController@index');

    Route::post('create', 'UserController@postAddUser');
  });
  //=================== USER =====================//

  //=================== API =====================//
  Route::group(['prefix' => 'api'], function(){
    Route::get('/', 'ApiController@index');
    Route::get('user/all', 'UserController@apiUser');
    Route::get('barang/all', 'BarangController@apiAllBarang');
    Route::get('barang/search/{barang}', 'BarangController@apiSearchBarang');
  });
  //=================== API =====================//

  //=================== SETTING =====================//
  Route::group(['prefix' => 'setting'], function(){
    Route::get('/','TokoController@index');

    Route::post('apply/theme', 'TokoController@applyTheme');
    Route::post('save/toko', 'TokoController@postEditToko');
  });
  //=================== SETTING =====================//

});

//=================== EXPORT =====================//
Route::group(['prefix' => 'export'], function () {
  Route::group(['prefix' => 'barang'], function () {
    Route::get('barangmasuk', 'ExportController@barangMasukToPdf');
  });
});
//=================== EXPORT =====================//

//=================== TEST AREA =====================//
Route::group(['prefix' => 'test'], function(){

  Route::post('submit', function(){
    return $_POST['topics'];
  });
  Route::get('detailmember', function()
  {
    $poin = App\Poin::all()->sortByDesc('harga_belanja');

    $belanja = 120000212;

    foreach ($poin as $poinList) {
      if ($belanja > $poinList->harga_belanja) {
        return $poinList->poin;
      }
    }
  });

  Route::get('diskon/{belanja}', 'TestController@setDiskon');

  Route::get('test/{id}', [
    'as' => 'test.id',
    'uses' => 'TestController@test'
  ]);

  Route::get('testonly', function(){
    //Barang terkecuali Ban dan Oli

    $barang_id = [1,2,3];
    $qty = [1,3,1];

    $count = count($barang_id);

    $penguranganTotalBelanja = 0;

    $queryBarangTerkecuali = App\Barang::where('nama_barang', 'LIKE', '%oli%')->get();

    foreach ($queryBarangTerkecuali as $barang) {
      if ($barang->nama_barang === 'oli') {
        echo "tidak identik";
        // $penguranganTotalBelanja =  50000 - ($barang->harga_jual * $qty[$i]);
      } else {
        return $barang->nama_barang;
      }
    }
    // return $penguranganTotalBelanja;
  });
});
//=================== TEST AREA =====================//
