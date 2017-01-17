<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="{{ action('PosController@dashboard') }}"><img src="{{ asset('images/logo/'.$toko->logo)}}" alt="" style="height: 32px ;margin-left: 20px; margin-top: 3px" /></a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <?php
          $user = App\User::find(Auth::user()->id); 
          $user->hasPermission('sales');
        ?>
        @if($user->can('sales'))
        <li>
          <a href="{{ action('PosController@dashboard') }}">
            <center>
              <i class="fa fa-shopping-cart fa-lg"></i>
              <br>Sales
            </center>
          </a>
        </li>
        @endif

        @if($user->can('return'))
        <li>
          <a href="{{ action('ReturnController@index') }}">
            <center>
              <i class="fa fa-arrow-circle-o-left fa-lg"></i>
              <br>Return
            </center>
          </a>
        </li>
        @endif
        
        @if($user->can('member'))
        <li>
          <a href="{{ action('MemberController@index') }}">
            <center><i class="fa fa-user fa-lg"></i>
            <br>Member</center>
          </a>
        </li>
        @endif

        @if($user->can('barang'))
        <li>
          <a href="{{ action('BarangController@getAddBarang') }}">
            <center>
              <i class="fa fa-truck fa-lg"></i>
              <br>Barang
            </center>
          </a>
        </li>
        @endif

        @if($user->can('poin'))
        <li>
          <a href="{{ action('BonusController@index') }}">
            <center>
              <i class="fa fa-gift fa-lg"></i>
              <br>Poin & Hadiah
            </center>
          </a>
        </li>
        @endif

        @if($user->can('user'))
        <li>
          <a href="{{ action('UserController@index') }}">
            <center>
              <i class="fa fa-users fa-lg"></i>
              <br>User & Akses
            </center>
          </a>
        </li>
        @endif

        @if($user->can('laporan'))
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><center><i class="fa fa-file fa-lg"></i><br>Laporan</center><span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li><a href="{{ action('ReportController@labaRugi') }}">Laba & Rugi</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="{{ action('ReportController@sales') }}">Penjualan</a></li>
            <li><a href="{{ action('ReportController@returns') }}">Return</a></li>
            <li><a href="{{ action('ReportController@member') }}">Member</a></li>
            <li><a href="{{ action('ReportController@barang') }}">Barang</a></li>
            <li><a href="{{ action('ReportController@penukaranPoin')}}">Penukaran Poin</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="{{ action('ReportController@user') }}">User</a></li>
          </ul>
        </li>
        @endif
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><marquee><h5><b>Tanggal : {{ date('d M Y') }}</b></h5></marquee></a></li>
        
        @if($user->can('setting'))
        <li><a href="{{ action('TokoController@index') }}"><center><i class="fa fa-wrench fa-lg"></i><br>Setting</center></a></li>
        @endif

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><center><i class="fa fa-user-secret fa-lg"></i><br>{{ App\User::find(Auth::user()->id)->name }}</center> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="{{ action('UserController@getProfile') }}">Profile</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="{{ action('UserController@keluar') }}"> Logout</a></li>
          </ul>
        </li>
      </ul>
    </div><!--/.nav-collapse -->
  </div><!--/.container-fluid -->
</nav>