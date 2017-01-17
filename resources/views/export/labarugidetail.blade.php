@extends('export.layout')

@section('header')
Laporan Penukaran Poin {{ App\Http\Controllers\LibraryController::waktuIndonesia($date) }}
@endsection

@section('title')
Laporan Penukaran Poin<br/>
<small>{{ App\Http\Controllers\LibraryController::waktuIndonesia($date) }}</small>
@endsection

@section('content')
<table border="1">
  <thead>
    <tr class="header">
      <th style="width: 9%"><center>Waktu</center></th>
      <th style="width: 12%"><center>No Nota</center></th>
      <th><center>Member</center></th>
      <th><center>Kasir</center></th>
      <th style="width: 15%"><center>Omset</center></th>
      <th style="width: 15%"><center>Modal</center></th>
      <th style="width: 15%"><center>Laba</center></th>
    </tr>
  </thead>
  <tbody>
    <?php 
      $omset = 0;  
      $modal = 0;
      $laba = 0;
    ?>
    @forelse($penjualan as $tranksaksi)
    <tr>
      <td><center>{{ App\Http\Controllers\LibraryController::timestampsToHour($tranksaksi->created_at) }} WIB</center></td>
      <td><center>{{ $tranksaksi->nota_id }}</center></td>
      <td><center>{{ $tranksaksi->nama_member }}</center></td>
      <td><center>{{ $tranksaksi->name }}</center></td>
      <td><center>Rp {{ number_format($tranksaksi->total) }}</center></td>
      <td><center>Rp {{ number_format($tranksaksi->modal) }}</center></td>
      <td><center>Rp {{ number_format($tranksaksi->laba) }}</center></td>
    </tr>
    <?php 
      $omset = $omset + $tranksaksi->total;
      $modal = $modal + $tranksaksi->modal;
      $laba = $laba + $tranksaksi->laba;

    ?>
    @empty
    <tr>
      <td colspan="7"><center><h3>Tidak Ada Data</h3></center></td>
    </tr>
    @endforelse
    <tr class="header">
      <td colspan="4"><center><strong>Total</strong></center></td>
      <td><center>Rp {{ number_format($omset) }}</center></td>
      <td><center>Rp {{ number_format($modal) }}</center></td>
      <td><center>Rp {{ number_format($laba) }}</center></td>
    </tr>
    
  </tbody>
</table>
@endsection