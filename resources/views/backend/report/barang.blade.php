@extends('layouts.main')

@section('title')
Laporan Barang
@endsection

@section('custom_styles')

@endsection

@section('content')
@include('partials.navbar')
@include('partials.alert')
<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-tabs" id="myTab">
      <li class="active"><a data-target="#aktivitas" data-toggle="tab">Barang Masuk</a></li>
      <li><a data-target="#editProfile" data-toggle="tab">Barang Keluar</a></li>
    </ul>

    <div class="tab-content">
      <div class="tab-pane active" id="aktivitas">
        <h3>Laporan Barang Masuk
          <div class="pull-right">
            <a href="{{ action('ExportController@barangMasukToPdf') }}" class="btn btn-sm btn-success">Cetak PDF</a>
          </div>
        </h3>
        <br>
        <table class="table table-hover" id="barangMasuk">
          <thead>
            <tr class="info">
              <th>No</th>
              <th>Nama Barang</th>
              <th>Stok Masuk</th>
              <th>Tanggal</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1 ?>
            @foreach($barangMasuk as $listBarang)
            <tr>
              <td>{{ $no++ }}</td>
              <td>{{ $listBarang->nama_barang }}</td>
              <td>+{{ $listBarang->stok_masuk }}</td>
              <td>{{ App\Http\Controllers\LibraryController::waktuIndonesia($listBarang->created_at) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="tab-pane" id="editProfile">
        <h3>Laporan Barang Keluar
          <div class="pull-right">
            <a href="{{ action('ExportController@barangMasukToPdf') }}" class="btn btn-sm btn-success">Cetak PDF</a>
          </div>
        </h3>
        <br>
        <table class="table table-hover table-striped table-bordered" id="barangKeluar">
          <thead>
            <tr class="info">
              <th style="width: 250px"><center>Tanggal</center></th>
              <th><center>Member</center></th>
              <th><center>Nama Barang</center></th>
              <th style="width: 40px"><center>Stok Keluar</center></th>
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
      </div>
    </div>



    <!-- <canvas id="barangMasuk" width="400" height="300"></canvas> -->
  </div>
</div>
@endsection

@section('custom_scripts')
<script src="{{ asset('js/Chart.min.js') }}"></script>

<script type="text/javascript">
$(document).ready(function() {
  $('#barangMasuk').DataTable();
  });
</script>

<script type="text/javascript">
$(document).ready(function() {
  $('#barangKeluar').DataTable();
  });
</script>

<script>
  var data = {
      labels: {!! json_encode($barang) !!},
      datasets: [
          {
              fillColor: "rgba(151,187,205,0.5)",
              strokeColor: "rgba(151,187,205,0.8)",
              pointColor: "rgba(220,220,220,1)",
              pointStrokeColor: "#fff",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(220,220,220,1)",
              highlightFill: "rgba(151,187,205,0.75)",
              highlightStroke: "rgba(151,187,205,1)",
              data: {!! json_encode($barangMasuk) !!}
          }
      ]
  };

  var ctx = document.getElementById("barangMasuk").getContext("2d");
  var myLineChart = new Chart(ctx).Bar(data);

</script>
@endsection
