@extends('export.layout')

@section('title')
<?php

  foreach($listBulan as $key => $value){
    if ($bulan == $key) {
      $stringBulan = $value;
    }
    // <option value="{{ $key }}">{{ $value }}</option>
  }

  $stringHeader = $stringBulan . ' ' . $tahun; 
?>
Laporan Laba Rugi<br/>
<small>{{ $stringHeader }}</small>
@endsection

@section('content')
<table class="table table-striped table-bordered" border="1">
  <thead>
    <tr class="header">
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
    @forelse($labarugi as $laba)
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
      <td style="text-align: center">{{ App\Http\Controllers\LibraryController::waktuIndonesia($laba->created_at) }}</td>
      <td style="text-align: center">Rp {{ number_format($laba->omset) }}</td>
      <td style="text-align: center">Rp {{ number_format($laba->modal) }}</td>
      <td style="text-align: center">Rp {{ number_format($labaTotal) }}</td>
    </tr>
    @empty
      <tr>
        <td colspan="4"><h3><center>Tidak ada Data</center></h3></td>
      </tr>
    @endforelse
    <tr class="header">
      <td><center><strong>TOTAL</strong></center></td>
      <td><center><strong>Rp {{ number_format($totalOmset) }}</strong></center></td>
      <td><center><strong>Rp {{ number_format($totalModal) }}</strong></center></td>
      <td><center><strong>Rp {{ number_format($totalLaba) }}</strong></center></td>
    </tr>
  </tbody>
</table>
@endsection