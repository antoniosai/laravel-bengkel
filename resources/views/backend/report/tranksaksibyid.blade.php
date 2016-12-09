@extends('layouts.main')

@section('title')
  Detail Nota
@endsection

@section('content')
@include('partials.navbar')
@include('partials.alert')
@include('partials.warning')

<div class="row">
  <div class="col-md-8">
    <h3>Detail Nota</h3>
    <hr>
    <div class="well">
      <div class="row">
        <div class="col-md-4">
          <h5>No Nota</h5>
          <h4><strong>01123131232</strong></h4>
        </div>
        <div class="col-md-4">
          <h5>Tanggal</h5>
          <h4><strong>{{ date('d M Y')}}</strong></h4>
        </div>
        <div class="col-md-4">
          <h5>Operator</h5>
          <h4><strong>Antonio Saiful Islam</strong></h4>
        </div>
      </div>
    </div>
    <center><h4>Detail Belanja</h4></center>
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>No</th>
          <th>Barang</th>
          <th>Harga</th>
          <th>Qty</th>
          <th>Sub Total</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $no = 1;
          $total = 0;
          $diskon = 0;
          $subtotal = 0;
        ?>
        @foreach($tranksaksi as $listTranksaksi)
        <?php
          $total = $total + $listTranksaksi->total;
          $subtotal = $subtotal + $listTranksaksi->subtotal;
          $diskon = $listTranksaksi->diskon;
        ?>
        <tr>
          <td>{{ $no++ }}</td>
          <td>{{ $listTranksaksi->nama_barang }}</td>
          <td>Rp {{ number_format($listTranksaksi->harga) }}</td>
          <td>{{ $listTranksaksi->qty }}</td>
          <td>Rp {{ number_format($listTranksaksi->subtotal) }}</td>
        </tr>
        @endforeach
        <tr>
          <td colspan="4">Total Rp {{ $subtotal }}</td>
          <td>Rp {{ number_format($total) }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection
