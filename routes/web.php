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

    Route::get('order/delete/{orderid}/{barangid}', [
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

    Route::get('generate/excel', 'BarangController@generateExcelTemplate');

    Route::post('import/barang', 'BarangController@importBarang');
  });
  //=================== BARANG =====================//


  //=================== MEMBER =====================//
  Route::group(['prefix' => 'member'], function(){
    Route::get('/', 'MemberController@index');
    Route::post('add', 'MemberController@postAddMember');
    Route::post('edit', 'MemberController@postEditMember');

    Route::get('delete/{id}', [
      'as' => 'member.delete',
      'uses' => 'MemberController@deleteMember'
    ]);
  });
  //=================== MEMBER =====================//

  //=================== REPORT =====================//
  Route::group(['prefix' => 'report'], function(){
    Route::get('/', 'ReportController@index');

    //Sales
    Route::group(['prefix' => 'sales'], function(){
      Route::get('/', 'ReportController@sales');
      Route::post('/', 'ReportController@postSales');
      Route::get('/{bulan}/{tahun}', 'ReportController@salesByDate');
    });
    //End Sales

    //Member
    Route::group(['prefix' => 'member'], function(){
      
      Route::get('/', 'ReportController@member');

      Route::get('{id}', [
        'as' => 'report.member',
        'uses' => 'ReportController@memberById'
      ]);

    });
    //End Member
    
    //Barang
    Route::group(['prefix' => 'barang'], function(){
      Route::get('/', 'ReportController@barang');
      Route::get('{bulan}/{tahun}', [
        'as' => 'barang.date',
        'uses' => 'ReportController@barangByDate'
      ]);
    });
    //End Barang
    
    //User
    Route::group(['prefix' => 'user'], function(){
      Route::get('/', 'ReportController@user');
      Route::get('/{id}', [
        'as' => 'report.user.byid',
        'uses' => 'ReportController@userById'
      ]);
    });
    //End User

    //Laba Rugi
    Route::group(['prefix' => 'labarugi'], function(){
      Route::get('/', 'ReportController@labaRugi');
      // Route::get('labarugi', 'ReportController@labaRugiByDate');
      // Route::get('labarugi/{bulan}/{tahun}', [
      //   'as' => 'labarugi.short',
      //   'uses' => 'ReportController@labaRugi'
      // ]);
      Route::get('/{bulan}/{tahun}', 'ReportController@labaRugiByDate');

      Route::get('{created_at}', [
        'as' => 'labarugi.detail',
        'uses'=> 'ReportController@labaRugiDetail'
      ]);
      
      Route::get('{tanggal}/{bulan}/{tahun}', 'ReportController@labaRugiBySingleDate');
      
      Route::post('/', 'ReportController@postLabaRugi');
    });
    //End Laba Rugi

    // Start of Report Penukaran Poin
    Route::group(['prefix' => 'penukaran_poin'], function(){
      Route::get('/', 'ReportController@penukaranPoin');
      Route::get('{bulan}/{tahun}', 'ReportController@penukaranPoinByDate');
      Route::post('/', 'ReportController@postPenukaranPoin');
    });
    // End of Report Penukaran Poin

  });
  //=================== REPORT =====================//

  //=================== BONUS =====================//
  Route::group(['prefix' => 'bonus'], function(){
    Route::get('/', 'BonusController@index');
    Route::post('bonus/add/hadiah', 'BonusController@postAddHadiah');
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

  Route::group(['prefix' => 'labarugi'], function () {
    
    Route::get('{bulan}/{tahun}', [
      'as' => 'laba_rugi.pdf',
      'uses' => 'ExportController@labaRugiToPdf'
    ]);

    Route::get('{date}', [
      'as' => 'laba_rugi_detail.pdf',
      'uses' => 'ExportController@labaRugiDetailToPdf'
    ]);

  });

  Route::group(['prefix' => 'barang'], function () {
    
    Route::get('barangmasuk', [
      'as' => 'barang_masuk.pdf',
      'uses' => 'ExportController@barangMasukToPdf'
    ]);

  });

  Route::group(['prefix' => 'penukaran_poin'], function(){
    Route::get('{bulan}/{tahun}', [
      'as' => 'penukaran_poin.pdf',
      'uses' => 'ExportController@penukaranPoinToPdf'
    ]);
  });

  Route::group(['prefix' => 'penjualan'], function(){
    Route::get('{bulan}/{tahun}', [
      'as' => 'penjualan.pdf',
      'uses' => 'ExportController@penjualanToPdf'
    ]);

    Route::get('{nota}', [
      'as' => 'nota.pdf',
      'uses' => 'ExportController@fakturToPdf'
    ]);
  });
});
//=================== EXPORT =====================//

//=================== TEST AREA =====================//
Route::group(['prefix' => 'test'], function(){

  Route::get('submit', function(){
    $thisYear = date('Y');
    $thisMonth = date('m');
    $thisDay = date('d');

    $labaRugiQuery = "
      SELECT *
      FROM laba_rugis
      WHERE month(created_at) = $thisMonth
      AND year(created_at) = $thisYear
      AND day(created_at) = $thisDay
    ";

    $labaRugi = App\LabaRugi::whereYear('created_at', $thisYear)
                        ->whereMonth('created_at', $thisMonth)
                        ->whereDay('created_at', $thisDay)
                        ->first();

    return $labaRugi->omset;
  });

  Route::get('detailmember', function()
  {
    return $orders_this_month = App\Barang::where( DB::raw('MONTH(created_at)', '=', date('m') ))->get();
  });

  Route::get('diskon/{belanja}', 'TestController@setDiskon');

  Route::get('test/{id}', [
    'as' => 'test.id',
    'uses' => 'TestController@test'
  ]);


  Route::get('repeat-until', function(){
    
    // Basic roles data
    App\Role::insert([
        ['name' => 'admin'],
        ['name' => 'manager'],
        ['name' => 'editor'],
    ]);
 
    // Basic permissions data
    App\Permission::insert([
        ['name' => 'access.backend'],
        ['name' => 'create.user'],
        ['name' => 'edit.user'],
        ['name' => 'delete.user'],
        ['name' => 'create.article'],
        ['name' => 'edit.article'],
        ['name' => 'delete.article'],
    ]);
 
    // Add a permission to a role
    $role = App\Role::where('name', 'admin')->first();
    $role->addPermission('access.backend');
    $role->addPermission('create.user');
    $role->addPermission('edit.user');    
    $role->addPermission('delete.user');
    // ... Add other role permission if necessary
 
    // Create a user, and give roles
    $user = App\User::find(18);
    $user->assignRole('admin');
 
    // ... Add other user and assign them to roles
  });
});
//=================== TEST AREA =====================//
