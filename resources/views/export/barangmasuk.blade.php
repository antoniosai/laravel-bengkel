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
Laporan Barang Masuk<br/>
<small>{{ $stringHeader }}</small>
@endsection

@section('custom_styles')
<style type="text/css">
  body {
  }
</style>
@endsection

@section('content')
<table border="1">
  <thead>
    <tr class="header">
      <th><center>No</center></th>
      <th><center>Nama Barang</center></th>
      <th><center>Stok  Masuk</center></th>
      <th><center>Operator</center></th>
      <th><center>Tanggal Masuk</center></th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; ?>
    @foreach($barangMasuk as $barang)
    <tr>
      <td><center>{{ $no++ }}</center></td>
      <td><center>{{ $barang->nama_barang }}</center></td>
      <td><center>{{ $barang->stok_masuk }}</center></td>
      <td><center>{{ App\User::find($barang->user_id)->name }}</center></td>
      <td><center>{{ App\Http\Controllers\LibraryController::waktuIndonesia($barang->created_at) }}</center></td>

    </tr>
    @endforeach
  </tbody>
</table>
@endsection