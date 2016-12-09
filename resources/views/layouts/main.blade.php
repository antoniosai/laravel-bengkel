<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>@yield('title')</title>

    <link href="{{ asset('css/font-awesome.min.css') }}" rel='stylesheet' type='text/css'>

    <?php $toko = App\Toko::all()->first(); ?>

    <link href="{{ asset('css/'.$toko->tema) }}" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://bootswatch.com/cosmo/bootstrap.min.css" media="screen" title="no title"> -->
    <link href="{{ asset('css/jquery.dataTables.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/selectize.css') }}" rel="stylesheet">
    <link href="{{ asset('css/selectize.bootstrap3.css') }}" rel="stylesheet">

    <link rel="icon" href="{{ asset('images/motor.png')}}">

    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />


    <script type="text/javascript" src="{{ asset('js/jquery.dataTables.min.js') }}"></script>

    <style media="screen">
    .navbar-nav > li > a, .navbar-brand {
      padding-top:4px !important;
      padding-bottom:0 !important;
      height: 28px;
    }
    .navbar {min-height:28px !important;}
    </style>
    @yield('custom_styles')
  </head>
  <body>

    <div class="container">
      @yield('content')
    </div>

    <script src="{{ asset('js/app.js') }}"></script>

    <script src="{{ asset('js/jquery-1.11.0.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('datatables/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('js/selectize.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    @yield('custom_scripts')
  </body>
</html>
