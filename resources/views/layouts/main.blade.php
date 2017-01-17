<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <?php $toko = App\Toko::all()->first(); ?>

    <link href="{!! asset('css/'.$toko->tema) !!}" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://bootswatch.com/cosmo/bootstrap.min.css" media="screen" title="no title"> -->
    <link href="{!! asset('css/jquery.dataTables.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/dataTables.bootstrap.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/selectize.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/selectize.bootstrap3.css') !!}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert.css') }}">

    <link rel="icon" href="{!! asset('images/logo/motor.png')!!}">

    <link rel="stylesheet" type="text/css" href="{!! asset('font-awesome-4.7.0/css/font-awesome.min.css') !!}">


    <script type="text/javascript" src="{!! asset('js/jquery.dataTables.min.js') !!}"></script>

    <style media="screen">
    .navbar-nav > li > a, .navbar-brand {
      padding-top:4px !important;
      padding-bottom:0 !important;
      height: 48px;
    }
    .navbar {min-height:28px !important;}
  

    html {
  position: relative;
  min-height: 100%;
}
body {
  /* Margin bottom by footer height */
  margin-bottom: 160px;
}
footer {
  position: absolute;
  bottom: 0;
  width: 100%;
  /* Set the fixed height of the footer here */
  height: 80px;
  background-color: #f5f5f5;
}

.nav-button {
  position: absolute;
  margin: auto;
  bottom: 0;
}
    </style>
    @yield('custom_styles')
  </head>
  <body onload="location.href='#barang_id'">

    <div class="container">
      @include('partials.navbar')
      @include('partials.alert')
      @include('partials.warning')
      @include('partials.validationmessage')
      @yield('content')
    </div>

    <script src="{!! asset('js/app.js') !!}"></script>

    <script src="{!! asset('js/jquery-1.11.0.js') !!}"></script>
    <script src="{!! asset('js/bootstrap.min.js') !!}"></script>
    <script src="{!! asset('datatables/jquery.dataTables.js') !!}"></script>
    <script src="{!! asset('datatables/dataTables.bootstrap.js') !!}"></script>
    <script src="{!! asset('js/selectize.min.js') !!}"></script>
    <script src="{!! asset('js/app.js') !!}"></script>
    <script src="{!! asset('js/sweetalert.min.js') !!}"></script>

    @yield('custom_scripts')
    
    <footer>
      <div class="container">
        <p class="text-muted">
          <center>
            <strong>{!! $toko->nama_toko !!} &copy;{!! date('Y') !!}</strong>
            <br>
            <span class="fa fa-envelope-o fa-lg"></span> {!! $toko->email !!}&nbsp;&nbsp;&nbsp;&nbsp;
            <span class="fa fa-phone fa-lg"></span> {!! $toko->telepon !!}
            <br>
            {!! $toko->alamat !!}
          </center>
        </p>
      </div>
    </footer>
  </body>
</html>
