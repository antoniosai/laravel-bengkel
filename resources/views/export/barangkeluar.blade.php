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
Laporan Barang Keluar<br/>
<small>{{ $stringHeader }}</small>
@endsection

@section('custom_styles')
<style type="text/css">
  body {
  }
</style>
@endsection

@section('content')
<table class="table table-hover table-striped table-condensed" id="barangKeluar" border="1">
  <thead>
    <tr class="header">
      <th style="width: 140px"><center>Tanggal</center></th>
      <th><center>Member</center></th>
      <th><center>Nama Barang</center></th>
      <th style="width: 100px"><center>Stok Keluar</center></th>
      <th style="width: 150px"><center>Jenis Tranksaksi</center></th>
    </tr>
  </thead>
  <tbody>
    @foreach($barangKeluar as $listBarang)
    <tr>
      <td><center>{{ App\Http\Controllers\LibraryController::waktuIndonesiaWithSecond($listBarang->created_at) }}</center></td>
      <td><center>{{ $listBarang->nama_member }}</center></td>
      <td><center>{{ $listBarang->nama_barang }}</center></td>
      <td><center>-{{ $listBarang->stok_keluar }}</center></td>
      <td><center>{{ $listBarang->tranksaksi }}</center></td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection