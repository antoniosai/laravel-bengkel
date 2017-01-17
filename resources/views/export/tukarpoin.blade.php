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
Laporan Penukaran Poin {{ $stringHeader }}
@endsection

@section('title')
Laporan Penukaran Poin<br/>
<small>{{ $stringHeader }}</small>
@endsection

@section('content')
<table border="1">
  <thead>
    <tr class="header">
      <th style="width: 20%"><center>Tanggal Penukaran</center></th>
      <th><center>Nama Member</center></th>
      <th><center>Barang</center></th>
      <th><center>Operator</center></th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1 ?>
    @foreach($tukarpoin as $listPoin)
    <tr>
      <td><center>{{ App\Http\Controllers\LibraryController::waktuIndonesia($listPoin->created_at) }}</center></td>
      <td><center>{{ $listPoin->nama_member }}</center></td>
      <td><center>{{ $listPoin->nama_barang }}</center></td>
      <td><center>{{ $listPoin->name }}</center></td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection