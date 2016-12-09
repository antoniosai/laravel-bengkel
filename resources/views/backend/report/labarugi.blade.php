@extends('layouts.main')

@section('title')
Laporan Laba Rugi
@endsection

@section('custom_styles')

@endsection

@section('content')
@include('partials.navbar')
@include('partials.alert')
<div class="well">
  <div class="row">
    <div class="col-md-3">
      <h3>Laporan Laba Rugi</h3>
    </div>
    <div class="col-md-9" style="margin-top: 10px">
        <form class="form-inline pull-right" action="index.html" method="post">

        <select class="form-control" name="bulan">
          <option value="1">Januari</option>
          <option value="2">Februari</option>
          <option value="3">Maret</option>
          <option value="4">April</option>
          <option value="5">Mei</option>
          <option value="6">Juni</option>
          <option value="7">Juli</option>
          <option value="8">Agustus</option>
          <option value="9">September</option>
          <option value="10">Oktober</option>
          <option value="11">November</option>
          <option value="12">Desember</option>
        </select>

        <select class="form-control" name="bulan">
          <option value="1">2016</option>
        </select>
      <button type="submit" class="btn btn-success">Sort</button>

      </form>
    </div>
  </div>
</div>
<h2>Bulan Januari 2016
  <div class="pull-right">
    <a href="#" class="btn btn-primary">Cetak PDF</a>
  </div>
</h2>
<hr>
<table class="table table-bordered table-hover">
  <thead>
    <tr>
      <th>TANGGAL</th>
      <th>OMSET</th>
      <th>MODAL</th>
      <th>LABA</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>12 Januari 2016</td>
      <td>Rp {{ number_format(50000) }}</td>
      <td>Rp {{ number_format(50000) }}</td>
      <td>Rp {{ number_format(50000) }}</td>
    </tr>
    <tr>
      <td>12 Januari 2016</td>
      <td>Rp {{ number_format(50000) }}</td>
      <td>Rp {{ number_format(50000) }}</td>
      <td>Rp {{ number_format(50000) }}</td>
    </tr>
    <tr>
      <td>12 Januari 2016</td>
      <td>Rp {{ number_format(50000) }}</td>
      <td>Rp {{ number_format(50000) }}</td>
      <td>Rp {{ number_format(50000) }}</td>
    </tr>
  </tbody>
</table>
<!-- <ul class="nav nav-tabs" id="myTab">
  <li class="active"><a data-target="#tranksaksi" data-toggle="tab">Tranksaksi</a></li>
  <li><a data-target="#penukaranpoin" data-toggle="tab">Penukaran Poin</a></li>
</ul>

<div class="tab-content">
  <div class="tab-pane active" id="tranksaksi">
    <div class="row">
      <div class="col-md-12">
        <h3>Laporan Tranksaksi</h3>
      </div>

      <div class="col-md-12 pull-right">

      </div>
    </div>
  </div>

  <div class="tab-pane" id="penukaranpoin">
    <h3>Penukaran Poin Barang</h3>
  </div>
</div> -->
@endsection

@section('custom_scripts')
<script type="text/javascript">
$(document).ready(function() {
  $('#labaRugi').DataTable();
  });
</script>
@endsection
