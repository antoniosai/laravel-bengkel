@extends('layouts.main')

@section('title')
  Daftar Barang
@endsection

@section('content')
<div class="well">
  <h3>Return <small>menampilkan data penjualan hari ini</small>
  </h3>
</div>

<table class="table table-hover table-striped table-condensed" id="returns">
  <thead>
    <tr class="info">
      <th style="width: 21%"><center>Tanggal</center></th>
      <th><center>No Nota</center></th>
      <th><center>No Faktur</center></th>
      <th><center>Member</center></th>
      <th><center>Total Belanja</center></th>
      <th><center>Kasir</center></th>
      <th><center>Aksi</center></th>
    </tr>
  </thead>
  <tbody>
    @foreach($tranksaksi as $listTranksaksi)
    <tr>
      <td><center>{{ App\Http\Controllers\LibraryController::waktuIndonesiaWithSecond($listTranksaksi->created_at) }}</center></td>
      <td><center>{{ $listTranksaksi->nota_id }}</center></td>
      <td><center>{{ $listTranksaksi->faktur_id }}</center></td>
      <td><center>{{ $listTranksaksi->nama_member }}</center></td>
      <td><center>Rp {{ number_format($listTranksaksi->total) }}</center></td>
      <td><center>{{ $listTranksaksi->name }}</center></td>
      <td><center><a href="{{ route('nota.detail', $listTranksaksi->nota_id) }}" class="btn btn-xs btn-info">Return</a></center></td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection

@section('custom_scripts')
<script type="text/javascript">
  $(function() {
    $("#returns").dataTable();
  });
</script>
@endsection
