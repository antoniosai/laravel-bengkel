@extends('layouts.main')

@section('title')
Laporan Penjualan
@endsection

@section('custom_styles')
<script src="{{ asset('js/jquery.min-1.9.1.1.js') }}"></script>
<script src="{{ asset('js/bootstrap-filestyle.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/switch.css') }}">
@endsection

@section('content')
<ul class="nav nav-tabs" id="myTab">
  <li class="active"><a data-target="#toko" data-toggle="tab">Bengkel</a></li>
  <li><a data-target="#tema" data-toggle="tab">Tema</a></li>
  <li><a data-target="#pemutihan" data-toggle="tab">Pemutihan Data</a></li>
</ul>

<div class="tab-content">
  <div class="tab-pane active" id="toko">
    <div class="row">
      <div class="col-md-12">
        <h3>Setting Informasi Bengkel</h3>
        <form class=" col-md-6" action="{{ action('TokoController@postEditToko') }}" method="post">
          {{ csrf_field() }}
          <input type="hidden" name="id" value="{{ $toko->id }}">
          <div class="form-group">
            <label>Nama Bengkel</label>
            <input type="text" name="nama_toko" value="{{ $toko->nama_toko }}" placeholder="Nama Toko" class="form-control">
          </div>
          <div class="form-group">
            <label>Nomor Telepon</label>
            <input type="text" name="telepon" value="{{ $toko->telepon }}" placeholder="Nomor Telepon" class="form-control">
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="text" name="email" value="{{ $toko->email }}" placeholder="email" class="form-control">
          </div>
          <div class="form-group">
            <label>Alamat Bengkel</label>
            <textarea name="alamat" rows="4" cols="40" class="form-control">{{ $toko->alamat }}</textarea>
          </div>
          <button type="submit" class="btn btn-success pull-right">Simpan</button>
        </form>
      </div>
    </div>
  </div>

  <div class="tab-pane" id="tema">
    <div class="col-md-8">
      <h3>Pilih Tampilan</h3>
      <hr>
      <form action="{{ action('TokoController@applyTampilan') }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        
        <div class="form-group">
          <label>Pilih Logo <small><i>Saran: height:32px</i></small></label>
          <input type="file" class="filestyle" name="logo" data-icon="false">
        </div>

        <div class="form-group">
          <label>Pilih Wallpaper Halaman Login</label>
          <input type="file" class="filestyle" name="login" data-icon="false">
        </div>

        <div class="form-group">
          <label>Pilih Logo Halaman Login</label>
          <input type="file" class="filestyle" name="login_logo" data-icon="false">
        </div>

        <div class="pull-right">
          <button type="submit" class="btn btn-success">Simpan Tampilan</button>
        </div>

      </form>
    </div>
    <div class="col-md-4">
      <h3>Pilih Tema</h3>
      <hr>
      <form action="{{ action('TokoController@applyTheme') }}" method="post">
        {{ csrf_field() }}
        <div class="form-group">
          <label></label>
          <select class="form-control" name="tema">
            @foreach($tema as $theme)
            <option value="{{ $theme->path }}">{{ $theme->nama_tema }}</option>
            @endforeach
          </select>
        </div>
        <button type="submit" class="btn btn-success">Ganti Tema</button>
      </form>
    </div>
  </div>

  <div class="tab-pane" id="pemutihan">
    <h3>
      Pemutihan Data Toko
      <br>
      <small>Menghapus data-data toko</small>
    </h3>
    <div class="well">
      {{-- <form> --}}
      <form method="POST" action="{{ action('TokoController@pemutihanData') }}">
        {{ csrf_field() }}
        <!-- List group -->
        <label>Pilih Laporan Yang Akan dihapus. <strong><i>*) Cetak atau Download terlebih dahulu laporan sebelum diproses</i></strong></label>
        <ul class="list-group">
          <?php 
          $thisDay = date('d');
          $thisMonth = date('m');
          $thisYear = date('Y');
          ?>
          <li class="list-group-item">
            Laporan Penjualan&emsp;<a href="{{ url('export/penjualan/'.$thisMonth.'/'.$thisYear.'') }}" class="btn btn-xs btn-info"><i class="fa fa-file-pdf-o fa-lg"></i> Download PDF</a>
            <div class="material-switch pull-right">
              <input id="tranksaksi" name="laporan[]" value="tranksaksi" type="checkbox"/>
              <label for="tranksaksi" class="label-danger"></label>
            </div>
          </li>
          <li class="list-group-item">
            Laporan Laba Rugi&emsp;<a href="{{ url('export/labarugi/'.$thisMonth.'/'.$thisYear.'') }}" class="btn btn-xs btn-info"><i class="fa fa-file-pdf-o fa-lg"></i> Download PDF</a>
            <div class="material-switch pull-right">
              <input id="labarugi" name="laporan[]" value="labarugi" type="checkbox"/>
              <label for="labarugi" class="label-danger"></label>
            </div>
          </li>
          <li class="list-group-item">
            Laporan Barang Masuk&emsp;<a href="{{ url('export/labarugi/1/2017') }}" class="btn btn-xs btn-info"><i class="fa fa-file-pdf-o fa-lg"></i> Download PDF</a>
            <div class="material-switch pull-right">
              <input id="barangmasuk" name="laporan[]" value="barangmasuk" type="checkbox"/>
              <label for="barangmasuk" class="label-danger"></label>
            </div>
          </li>
          <li class="list-group-item">
            Laporan Barang Keluar&emsp;<a href="" class="btn btn-xs btn-info"><i class="fa fa-file-pdf-o fa-lg"></i> Download PDF</a>
            <div class="material-switch pull-right">
              <input id="barangkeluar" name="laporan[]" value="barangkeluar" type="checkbox"/>
              <label for="barangkeluar" class="label-danger"></label>
            </div>
          </li>
          <li class="list-group-item">
            Laporan Penukaran Poin&emsp;<a href="" class="btn btn-xs btn-info"><i class="fa fa-file-pdf-o fa-lg"></i> Download PDF</a>
            <div class="material-switch pull-right">
              <input id="penukaranpoin" name="laporan[]" value="penukaranpoin" type="checkbox"/>
              <label for="penukaranpoin" class="label-danger"></label>
            </div>
          </li>
          <li class="list-group-item">
            Laporan Return&emsp;<a href="" class="btn btn-xs btn-info"><i class="fa fa-file-pdf-o fa-lg"></i> Download PDF</a>
            <div class="material-switch pull-right">
              <input id="returns" name="laporan[]" value="returns" type="checkbox"/>
              <label for="returns" class="label-danger"></label>
            </div>
          </li>
        </ul>
        <hr>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Password Admin</label>
              <input type="password" name="password" class="form-control">
            </div>  
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Konfirmasi Password Admin</label>
              <input type="password" name="password_confirmation" class="form-control">
            </div>    
          </div>
        </div>

        <div class="clearfix">
          <div class="pull-right">
            <button type="submit" class="btn btn-success">Proses</button>
          </div>
        </div>
      </form>
    </div>
    
  </div>
</div>

@include('partials.form._pemutihan')

@endsection

@section('custom_scripts')

<script type="text/javascript">
  $(document).ready(function() {
    $('#tukarPoin').DataTable();
  });
</script>

<script type="text/javascript">
  $(document).ready(function() {
    $('#tranksaks').DataTable();
  });
</script>
@endsection
