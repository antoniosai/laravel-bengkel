@extends('layouts.main')

@section('title')
Laporan Penjualan
@endsection

@section('custom_styles')

@endsection

@section('content')
@include('partials.navbar')
@include('partials.alert')
<div class="row">
  <div class="col-md-12">

    <ul class="nav nav-tabs" id="myTab">
      <li class="active"><a data-target="#tranksaksi" data-toggle="tab">Tranksaksi</a></li>
      <li><a data-target="#penukaranpoin" data-toggle="tab">Penukaran Poin</a></li>
    </ul>

    <div class="tab-content">
      <div class="tab-pane active" id="tranksaksi">
        <div class="row">
          <div class="col-md-12">
            <h3>Laporan Tranksaksi
              <div class="pull-right">
                <a href="{{ action('ExportController@barangMasukToPdf') }}" class="btn btn-sm btn-success">Cetak PDF</a>
                <a href="{{ action('ExportController@barangMasukToPdf') }}" class="btn btn-sm btn-success">Cetak PDF</a>
              </div>
            </h3>
          </div>

          <div class="col-md-12 pull-right">
            <br>
            <form class="form-input" action="index.html" method="post">
              <div class="row">
                <div class="col-md-2">
                  <div class="form-group">
                    <select class="form-control" name="bulan">
                      <option value="1">Januari</option>
                      <option value="2">Februari</option>
                      <option value="3">Maret</option>
                      <option value="4">April</option>
                      <option value="5">Mei</option>
                      <option value="6">Juni</option>
                      <option value="7">Juli</option>
                      <option value="8">Agustus</option>
                      <option value="9">September</option>
                      <option value="10">Oktober</option>
                      <option value="11">November</option>
                      <option value="12">Desember</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <select class="form-control" name="bulan">
                      <option value="1">2016</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <a href="{{ action('ExportController@barangMasukToPdf') }}" class="btn btn-success">Cetak PDF</a>
                </div>
              </div>
            </form>
          </div>

        </div>
        <hr>
        <table class="table table-hover" id="tranksaks">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>No Nota</th>
              <th>Member</th>
              <th>Subtotal</th>
              <th><center>Diskon</center></th>
              <th>Total Belanja</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($tranksaksi as $listTranksaksi)
            <tr>
              <td>{{ App\Http\Controllers\LibraryController::waktuIndonesia($listTranksaksi->created_at) }}</td>
              <td>{{ $listTranksaksi->nota_id }}</td>
              <td>{{ $listTranksaksi->nama_member }}</td>
              <td>Rp {{ number_format($listTranksaksi->subtotal) }}</td>
              <td><center>{{ $listTranksaksi->diskon }}%</center></td>
              <td>Rp {{ number_format($listTranksaksi->total) }}</td>
              <td><a href="{{ route('nota.detail', $listTranksaksi->nota_id) }}" class="btn btn-xs btn-info">Detail</a></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="tab-pane" id="penukaranpoin">
        <h3>Penukaran Poin Barang</h3>
        <hr>
        <table class="table table-hover table-striped" id="tukarPoin">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>Member</th>
              <th>Barang</th>
              <th>Operator</th>
            </tr>
          </thead>
          <tbody>
            @foreach($tukar_poin as $tukarPoin)
            <tr>
              <td>{{ App\Http\Controllers\LibraryController::waktuIndonesia($tukarPoin->created_at) }}</td>
              <td>{{ $tukarPoin->nama_member }}</td>
              <td>{{ $tukarPoin->nama_barang }}</td>
              <td>{{ $tukarPoin->name }}</td>
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
<script type="text/javascript">
$(document).ready(function() {
  $('#tukarPoin').DataTable();
  });
</script>

<script type="text/javascript">
$(document).ready(function() {
  $('#tranksaks').DataTable();
  });
</script>
@endsection
