@extends('layouts.main')

@section('title')
Laporan Member
@endsection

@section('custom_styles')

@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
      
    <ul class="nav nav-tabs" id="myTab">
      <li class="active"><a data-target="#tranksaksi" data-toggle="tab">Tranksaksi</a></li>
      <li><a data-target="#penukaranpoin" data-toggle="tab">Penukaran Poin</a></li>
      <li><a data-target="#barangmasuk" data-toggle="tab">Barang Masuk</a></li>
    </ul>

    <div class="tab-content">
      <div class="tab-pane active" id="tranksaksi">
        <h4>Menampilkan Data Tranksaksi yang dilakukan oleh {{ $user->name }}</h4>
        <hr>
        <table class="table table-hover table-condensed" id="sales">
          <thead>
            <tr class="info">
              <th style="width: 21%"><center>Tanggal</center></th>
              <th><center>No Nota</center></th>
              <th><center>Member</center></th>
              <th><center>Subtotal</center></th>
              <th><center>Total Belanja</center></th>
              <th><center>Aksi</center></th>
            </tr>
          </thead>
          <tbody>
            @foreach($penjualan as $listTranksaksi)
            <tr>
              <td><center>{{ App\Http\Controllers\LibraryController::waktuIndonesiaWithSecond($listTranksaksi->created_at) }}</center></td>
              <td><center>{{ $listTranksaksi->nota_id }}</center></td>
              <td><center>{{ $listTranksaksi->nama_member }}</center></td>
              <td><center>Rp {{ number_format($listTranksaksi->subtotal) }}</center></td>
              <td><center>Rp {{ number_format($listTranksaksi->total) }}</center></td>
              <td><center><a href="{{ route('nota.detail', $listTranksaksi->nota_id) }}" class="btn btn-xs btn-info">Detail</a></center></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="tab-pane" id="penukaranpoin">
        <h4>Menampilkan Data Penukaran Poin yang dilakukan oleh {{ $user->name }}</h4>
        <hr>
        <table class="table table-hover table-striped" id="poin">
          <thead>
            <tr class="info">
              <th><center>Tanggal Penukaran</center></th>
              <th><center>Nama Member</center></th>
              <th><center>Barang</center></th>
              <th><center>Operator</center></th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1 ?>
            @foreach($penukaran_poin as $listPoin)
            <tr>
              <td><center>{{ App\Http\Controllers\LibraryController::waktuIndonesia($listPoin->created_at) }}</center></td>
              <td><center>{{ $listPoin->nama_member }}</center></td>
              <td><center>{{ $listPoin->nama_barang }}</center></td>
              <td><center>{{ $listPoin->name }}</center></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>


      <div class="tab-pane" id="barangmasuk">
        <h4>Menampilkan Data Barang Masuk yang dilakukan oleh {{ $user->name }}</h4>
        <hr>
        <table class="table table-hover table-striped" id="barang">
          <thead>
            <tr class="info">
              <th style="width:220px"><center>Tanggal Masuk</center></th>
              <th><center>Barang</center></th>
              <th style="width: 120px"><center>+Stok Masuk</center></th>
              <th style="width: 120px"><center>Keterangan</center></th>
              <th><center>Operator</center></th>
            </tr>
          </thead>
          <tbody>
            @foreach($barang_masuk as $barang)
            <tr>
              <td><center>{{ App\Http\Controllers\LibraryController::waktuIndonesia($barang->created_at) }}</center></td>
              <td><center>{{ $barang->nama_barang }}</center></td>
              <td><center>{{ $barang->stok_masuk }}</center></td>
              <td><center>{{ $barang->detail }}</center></td>
              <td><center>{{ $barang->name }}</center></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>


  </div>
</div>
@endsection

@section('custom_scripts')
<script src="{{ asset('js/Chart.min.js') }}"></script>

<script type="text/javascript">
$(document).ready(function() {
  $('#sales').DataTable();
  $('#poin').DataTable();
  $('#barang').DataTable();
});
</script>


@endsection
