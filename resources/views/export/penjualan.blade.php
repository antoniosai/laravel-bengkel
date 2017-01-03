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
Laporan Penjualan{{ $stringHeader }}
@endsection

@section('title')
Laporan Penjualan<br/>
<small>{{ $stringHeader }}</small>
@endsection

@section('content')
<table>
  <thead>
    <tr class="header">
      <th><center>Tanggal</center></th>
      <th style="width: 11%"><center>No Nota</center></th>
      <th style="width: 11%"><center>No Faktur</center></th>
      <th><center>Member</center></th>
      <th style="width: 13%"><center>Total Belanja</center></th>
      <th><center>Kasir</center></th>
    </tr>
  </thead>
  <tbody>
    @foreach($penjualan as $listTranksaksi)
    <tr>
      <td><center>{{ App\Http\Controllers\LibraryController::waktuIndonesiaWithSecond($listTranksaksi->created_at) }}</center></td>
      <td><center>{{ $listTranksaksi->nota_id }}</center></td>
      <td><center>{{ $listTranksaksi->faktur_id }}</center></td>
      <td><center>{{ $listTranksaksi->nama_member }}</center></td>
      <td><center>Rp {{ number_format($listTranksaksi->subtotal) }}</center></td>
      <td><center>{{ $listTranksaksi->name }}</center></td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection