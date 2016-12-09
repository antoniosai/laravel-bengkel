@extends('layouts.main')

@section('title')
Laporan Penjualan
@endsection

@section('custom_styles')

@endsection

@section('content')
@include('partials.navbar')
@include('partials.alert')
<ul class="nav nav-tabs" id="myTab">
  <li class="active"><a data-target="#toko" data-toggle="tab">Bengkel</a></li>
  <li><a data-target="#tema" data-toggle="tab">Tema</a></li>
</ul>

<div class="tab-content">
  <div class="tab-pane active" id="toko">
    <div class="row">
      <div class="col-md-12">
        <h3>Setting Informasi Bengkel</h3>
        <hr>
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
    <h3>Pilih Tema Tampilan</h3>
    <hr>
    <form action="{{ action('TokoController@applyTheme') }}" method="post">
      {{ csrf_field() }}
      <div class="form-group">
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
