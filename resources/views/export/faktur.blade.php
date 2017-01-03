@extends('export.layout')

@section('header')
Faktur
@endsection

@section('title')
Faktur
@endsection

@section('content')
<table class="table table-bordered table-hover table-condensed">
  <thead>
    <tr class="header">
      <th style="width: 5%"><center>No</center></th>
      <th><center>Barang</center></th>
      <th><center>Harga</center></th>
      <th style="width: 10%"><center>Jumlah</center></th>
      <th><center>Sub Total</center></th>
    </tr>
  </thead>
  <tbody>
    <?php
      $no = 1;
      $total = 0;
      $subtotal = 0;
    ?>
    @foreach($tranksaksi as $listTranksaksi)
    <?php
      $total = $total + $listTranksaksi->total;
      $subtotal = $subtotal + $listTranksaksi->subtotal;
    ?>
    <tr>
      <td><center>{{ $no++ }}</center></td>
      <td><center>{{ $listTranksaksi->nama_barang }}</center></td>
      <td><center>Rp {{ number_format($listTranksaksi->harga) }}</center></td>
      <td><center>{{ $listTranksaksi->qty }}</center></td>
      <td><center>Rp {{ number_format($listTranksaksi->subtotal) }}</center></td>
    </tr>
    @endforeach
    <tr class="header">
      <td colspan="4"><center><b>Total</b></center></td>
      <td><center>Rp {{ number_format($total) }}</center></td>
    </tr>
  </tbody>
</table>
@endsection