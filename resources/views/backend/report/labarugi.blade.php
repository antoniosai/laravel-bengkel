@extends('layouts.main')

@section('title')
Laporan Laba Rugi
@endsection

@section('custom_styles')

@endsection

@section('content')

<?php 
  $stringHeader = "Laporan Laba Rugi" 
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
    <div class="col-md-5">
      <h3>{{ $stringHeader }} <small><a href="#" title="Laporan Laba Rugi" data-toggle="popover" data-trigger="focus" data-content="Halaman untuk menampilkan laporan laba rugi (bulanan)"><i class="fa fa-question-circle fa-lg"></i></a></small></h3>
    </div>
    <div class="col-md-7" style="margin-top: 10px">
      <div class="pull-right">
        <form class="form-inline pull-right" action="{{ action('ReportController@postLabaRugi')}}" method="post">
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
            <button type="submit" class="btn btn-success" name="report" value="filter"><i class="fa fa-filter fa-lg"></i> Filter</button>
            <a href="{{ action('ReportController@labaRugi')}}" class="btn btn-warning"><i class="fa fa-times fa-lg"></i> Hapus Filter</a>
            <button type="submit" class="btn btn-primary" name="report" value="export"><i class="fa fa-file-pdf-o fa-lg"></i> Cetak PDF</button>
          </form>
      </div>
    </div>
  </div>
</div>

<table class="table table-striped table-condensed" id="labaRugi">
  <thead>
    <tr class="info">
      <th style="width: 15%; text-align: center">TANGGAL</th>
      <th style="width: 20%; text-align: center">OMSET</th>
      <th style="width: 20%; text-align: center">MODAL</th>
      <th style="width: 20%; text-align: center">LABA</th>
    </tr>
  </thead>
  <tbody>
    <?php 
      $totalOmset = 0;
      $totalModal = 0;
      $totalLaba = 0;
    ?>
    @foreach($laba_rugi as $laba)
    <?php 
      $labaTotal = $laba->omset - $laba->modal;
      $totalOmset = $totalOmset + $laba->omset; 
      $totalModal = $totalModal + $laba->modal;
      $totalLaba = $totalLaba + $labaTotal;

      $tahun = substr($laba->created_at, 0, 4);
      $bulan = substr($laba->created_at, 5, 2);
      $tgl   = substr($laba->created_at, 8, 2);

    ?>
    <tr>
      <td style="text-align: center">
        <a href="{{ route('labarugi.detail', App\Http\Controllers\LibraryController::timeStampToDate($laba->created_at)) }}">{{ App\Http\Controllers\LibraryController::waktuIndonesia($laba->created_at) }}
        </a>
      </td>
      <td style="text-align: center">Rp {{ number_format($laba->omset) }}</td>
      <td style="text-align: center">Rp {{ number_format($laba->modal) }}</td>
      <td style="text-align: center">Rp {{ number_format($labaTotal) }}</td>
    </tr>
    @endforeach
    <tfoot>
      <tr class="info">
        <td><center><strong>TOTAL</strong></center></td>
        <td><center><strong>Rp {{ number_format($totalOmset) }}</strong></center></td>
        <td><center><strong>Rp {{ number_format($totalModal) }}</strong></center></td>
        <td><center><strong>Rp {{ number_format($totalLaba) }}</strong></center></td>
      </tr>
    </tfoot>
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

<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});
</script>

@endsection
