@extends('layouts.main')

@section('title')
Laporan Member
@endsection

@section('custom_styles')

@endsection

@section('content')
@include('partials.navbar')
<?php 
  $stringHeader = "Laporan Penukaran Poin" 
?>

@if(!$bulan == null && !$tahun == null)
  <?php
    foreach($listBulan as $key => $value){
      if ($bulan == $key) {
        $stringBulan = $value;
      }
      // <option value="{{ $key }}">{{ $value }}</option>
    }

    $stringHeader .= " - Bulan : " . $stringBulan . ' ' . $tahun; 
  ?>
@else

@endif

<div class="well">
  <div class="row">
    <div class="col-md-6">
      <h3>{{ $stringHeader }} <small><a href="#" title="Laporan Laba Rugi" data-toggle="popover" data-trigger="focus" data-content="Halaman untuk menampilkan laporan laba rugi (bulanan)"><i class="fa fa-question-circle fa-lg"></i></a></small></h3>
    </div>
    <div class="col-md-6" style="margin-top: 10px">
      <div class="pull-right">
        <form class="form-inline pull-right" action="{{ action('ReportController@postPenukaranPoin')}}" method="post">
          {{ csrf_field() }}
            <select class="form-control" name="bulan">
              @foreach($listBulan as $key => $value)
                <?php $option = ''; ?>
                @if(date('M') == $key)
                  <?php $option = 'selected="selected"'; ?>
                @endif
                <option value="{{ $key }}" {{ $option }}>{{ $value }}</option>
              @endforeach
            </select>

            <select class="form-control" name="tahun">
              @foreach($listTahun as $value)
                <?php $option = ''; ?>
                @if(date('Y') == $value)
                  <?php $option = 'selected="selected"'; ?>
                @endif
                <option value="{{ $value }}" {{ $option }}>{{ $value }}</option>
              @endforeach
            </select>
            <button type="submit" class="btn btn-success" name="report" value="filter">Filter</button>
            <a href="{{ action('ReportController@penukaranPoin')}}" class="btn btn-info">Hapus Filter</a>
            <button type="submit" class="btn btn-primary" name="report" value="export">Cetak PDF</button>
          </form>
      </div>
    </div>
  </div>
</div>
@include('partials.alert')
<div class="row">
  <div class="col-md-12">
    <br>
    <table class="table table-hover table-striped table-bordered" id="poin">
      <thead>
        <tr class="info">
          <th style="width: 20%"><center>Tanggal Penukaran</center></th>
          <th><center>Nama Member</center></th>
          <th><center>Barang</center></th>
          <th><center>Operator</center></th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1 ?>
        @foreach($penukaran_poin as $listPoin)
        <tr>
          <td><center>{{ App\Http\Controllers\LibraryController::waktuIndonesia($listPoin->created_at) }}</center></td>
          <td><center>{{ $listPoin->nama_member }}</center></td>
          <td><center>{{ $listPoin->nama_barang }}</center></td>
          <td><center>{{ $listPoin->name }}</center></td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <!-- <canvas id="poinMember" width="400" height="300"></canvas> -->
  </div>
</div>
@endsection

@section('custom_scripts')
<script src="{{ asset('js/Chart.min.js') }}"></script>

<script type="text/javascript">
$(document).ready(function() {
  $('#poin').DataTable();
  });
</script>


@endsection
