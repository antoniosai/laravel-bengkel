  <nav class="navbar navbar-default ">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#"><img src="{{ asset('images/logo/logo-white.png')}}" alt="" style="width: 45px; margin-left: 20px" /></a>
    </div>
    <ul class="nav navbar-nav">

      <li><a href="{{ action('PosController@dashboard') }}">Sales</a></li>
      <li><a href="{{ action('MemberController@index') }}">Member</a></li>
      <li><a href="{{ action('BarangController@getAddBarang') }}">Barang</a></li>
      <li><a href="{{ action('BonusController@index') }}">Managemen Poin</a></li>
      <li><a href="{{ action('UserController@index') }}">User & Role</a></li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Laporan <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="{{ action('ReportController@labaRugi') }}">Laba & Rugi</a></li>
          <li role="separator" class="divider"></li>
          <li><a href="{{ action('ReportController@sales') }}">Penjualan</a></li>
          <li><a href="{{ action('ReportController@member') }}">Member</a></li>
          <li><a href="{{ action('ReportController@barang') }}">Barang</a></li>
          <li><a href="{{ action('ReportController@penukaranPoin')}}">Penukaran Poin</a></li>
          <li role="separator" class="divider"></li>
          <li><a href="{{ action('ReportController@user') }}">User</a></li>
        </ul>
      </li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="#"><marquee><b>Tanggal : {{ date('d M Y') }}</b></marquee></a></li>
      <li><a href="{{ action('TokoController@index') }}">Setting</a></li>
      <li><a href="{{ action('UserController@keluar') }}"> Logout</a></li>
    </ul>
  </div>
</nav>
