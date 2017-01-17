@extends('layouts.main')

@section('title')
Laporan Barang
@endsection

@section('custom_styles')

@endsection

@section('content')


@if(!$bulan == null && !$tahun == null)
  <?php
    foreach($listBulan as $key => $value){
      if ($bulan == $key) {
        $stringBulan = $value;
      }
      // <option value="{{ $key }}">{{ $value }}</option>
    }

    $stringHeader = $stringBulan . ' ' . $tahun; 
  ?>
@else
  <?php $stringHeader = 'Laporan'; ?>
@endif

<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-tabs" id="myTab">
      <li class="active"><a data-target="#asset" data-toggle="tab">Asset Barang</a></li>
      <li><a data-target="#aktivitas" data-toggle="tab">Barang Masuk</a></li>
      <li><a data-target="#editProfile" data-toggle="tab">Barang Keluar</a></li>
    </ul>

    <div class="tab-content">
      <div class="tab-pane active" id="asset">
        <div class="row">
          <div class="col-md-5">
            <h3>asds <small><a href="#" title="Laporan Laba Rugi" data-toggle="popover" data-trigger="focus" data-content="Halaman untuk menampilkan laporan laba rugi (bulanan)"><i class="fa fa-question-circle fa-lg"></i></a></small></h3>
          </div>
          <div class="col-md-7" style="margin-top: 10px">
            <div class="pull-right">
              <form class="form-inline pull-right" action="{{ action('ReportController@postFilterBarang')}}" method="post">
                <input type="hidden" name="barang" value="masuk">
                {{ csrf_field() }}
                <select class="form-control" name="bulan">
                  @foreach($listBulan as $key => $value)
                    <?php $option = ''; ?>
                    @if(date('M') == $key)
                      <?php $option = 'selected="selected"'; ?>
                    @endif
                    <option value="{{ $key }}" {{ $option }}>{{ $value }}</option>
                  @endforeach
                </select>

                <select class="form-control" name="tahun">
                  @foreach($listTahun as $value)
                    <?php $option = ''; ?>
                    @if(date('Y') == $value)
                      <?php $option = 'selected="selected"'; ?>
                    @endif
                    <option value="{{ $value }}" {{ $option }}>{{ $value }}</option>
                  @endforeach
                </select>
                <button type="submit" class="btn btn-success" name="report" value="filter"><i class="fa fa-filter fa-lg"></i> Filter</button>
                <a href="{{ action('ReportController@barang')}}" class="btn btn-warning"><i class="fa fa-times fa-lg"></i> Hapus Filter</a>
                <button type="submit" class="btn btn-primary" name="report" value="export"><i class="fa fa-file-pdf-o fa-lg"></i> Cetak PDF</button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <div class="tab-pane" id="aktivitas">
        <div class="row">
          <div class="col-md-5">
            <h3>{{ $stringHeader }} <small><a href="#" title="Laporan Laba Rugi" data-toggle="popover" data-trigger="focus" data-content="Halaman untuk menampilkan laporan laba rugi (bulanan)"><i class="fa fa-question-circle fa-lg"></i></a></small></h3>
          </div>
          <div class="col-md-7" style="margin-top: 10px">
            <div class="pull-right">
              <form class="form-inline pull-right" action="{{ action('ReportController@postFilterBarang')}}" method="post">
                <input type="hidden" name="barang" value="masuk">
                {{ csrf_field() }}
                  <select class="form-control" name="bulan">
                    @foreach($listBulan as $key => $value)
                      <?php $option = ''; ?>
                      @if(date('M') == $key)
                        <?php $option = 'selected="selected"'; ?>
                      @endif
                      <option value="{{ $key }}" {{ $option }}>{{ $value }}</option>
                    @endforeach
                  </select>

                  <select class="form-control" name="tahun">
                    @foreach($listTahun as $value)
                      <?php $option = ''; ?>
                      @if(date('Y') == $value)
                        <?php $option = 'selected="selected"'; ?>
                      @endif
                      <option value="{{ $value }}" {{ $option }}>{{ $value }}</option>
                    @endforeach
                  </select>
                  <button type="submit" class="btn btn-success" name="report" value="filter"><i class="fa fa-filter fa-lg"></i> Filter</button>
                  <a href="{{ action('ReportController@barang')}}" class="btn btn-warning"><i class="fa fa-times fa-lg"></i> Hapus Filter</a>
                  <button type="submit" class="btn btn-primary" name="report" value="export"><i class="fa fa-file-pdf-o fa-lg"></i> Cetak PDF</button>
                </form>
            </div>
          </div>
        </div>
        <br>
        <table class="table table-hover table-striped table-condensed" id="barangMasuk">
          <thead>
            <tr class="info">
              <th><center>No</center></th>
              <th><center>Nama Barang</center></th>
              <th><center>Stok Masuk</center></th>
              <th><center>Keterangan</center></th>
              <th><center>Tanggal</center></th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1 ?>
            @foreach($barangMasuk as $listBarang)
            <tr>
              <td><center>{{ $no++ }}</center></td>
              <td><center>{{ $listBarang->nama_barang }}</center></td>
              <td><center>+{{ $listBarang->stok_masuk }}</center></td>
              <td><center>{{ $listBarang->detail }}</center></td>
              <td>
                <center>
                  {{ App\Http\Controllers\LibraryController::waktuIndonesia($listBarang->created_at) }}
                </center>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="tab-pane" id="editProfile">
        <div class="row">
          <div class="col-md-5">
            <h3>{{ $stringHeader }} <small><a href="#" title="Laporan Laba Rugi" data-toggle="popover" data-trigger="focus" data-content="Halaman untuk menampilkan laporan laba rugi (bulanan)"><i class="fa fa-question-circle fa-lg"></i></a></small></h3>
          </div>
          <div class="col-md-7" style="margin-top: 10px">
            <div class="pull-right">
              <form class="form-inline pull-right" action="{{ action('ReportController@postFilterBarang')}}" method="post">
                <input type="hidden" name="barang" value="keluar">
                {{ csrf_field() }}
                  <select class="form-control" name="bulan">
                    @foreach($listBulan as $key => $value)
                      <?php $option = ''; ?>
                      @if(date('M') == $key)
                        <?php $option = 'selected="selected"'; ?>
                      @endif
                      <option value="{{ $key }}" {{ $option }}>{{ $value }}</option>
                    @endforeach
                  </select>

                  <select class="form-control" name="tahun">
                    @foreach($listTahun as $value)
                      <?php $option = ''; ?>
                      @if(date('Y') == $value)
                        <?php $option = 'selected="selected"'; ?>
                      @endif
                      <option value="{{ $value }}" {{ $option }}>{{ $value }}</option>
                    @endforeach
                  </select>
                  <button type="submit" class="btn btn-success" name="report" value="filter"><i class="fa fa-filter fa-lg"></i> Filter</button>
                  <a href="{{ action('ReportController@barang')}}" class="btn btn-warning"><i class="fa fa-times fa-lg"></i> Hapus Filter</a>
                  <button type="submit" class="btn btn-primary" name="report" value="export"><i class="fa fa-file-pdf-o fa-lg"></i> Cetak PDF</button>
                </form>
            </div>
          </div>
        </div>
        <br>
        <table class="table table-hover table-striped table-condensed" id="barangKeluar">
          <thead>
            <tr class="info">
              <th style="width: 180px"><center>Tanggal</center></th>
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
