<?php

  foreach($listBulan as $key => $value){
    if ($bulan == $key) {
      $stringBulan = $value;
    }
    // <option value="{{ $key }}">{{ $value }}</option>
  }

  $stringHeader = $stringBulan . ' ' . $tahun; 
?>

@extends('export.layout')

@section('header')
Laporan Return {{ $stringHeader }}
@endsection

@section('title')
Laporan Return<br/>
<small>{{ $stringHeader }}</small>
@endsection

@section('content')
<table class="table table-hover table-striped table-bordered" id="poin" border="1">
  <thead>
    <tr class="header">
      <th style="width: 120px"><center>Tanggal Return</center></th>
      <th><center>Nama Member</center></th>
      <th><center>Barang</center></th>
      <th style="width: 40px"><center>Qty</center></th>
      <th><center>Operator</center></th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1 ?>
    @foreach($returns as $pengembalian)
    <tr>
      <td><center>{{ App\Http\Controllers\LibraryController::waktuIndonesiaWithSecond($pengembalian->created_at) }}</center></td>
      <td><center>{{ $pengembalian->nama_member }}</center></td>
      <td><center>{{ $pengembalian->nama_barang }}</center></td>
      <td><center>{{ $pengembalian->qty }}</center></td>
      <td><center>{{ $pengembalian->name }}</center></td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection