@extends('layouts.main')

@section('title')
  Laba Rugi
@endsection

@section('content')

<div class="row">
  <div class="col-md-12">
    <h3>Detail Laporan Laba Rugi {{ App\Http\Controllers\LibraryController::waktuIndonesia($date) }}
      <div class="pull-right">
        <a href="javascript:history.go(-1)" class="btn btn-info btn-sm">Back</a>
        <a href="{{ route('laba_rugi_detail.pdf', $date) }}" class="btn btn-info btn-sm">Print to PDF</a>
      </div>
    </h3>
    <h4>
      <span class="label label-info">OMSET : Rp {{ number_format($laba_rugi->omset) }}</span>
      <span class="label label-success">MODAL : Rp {{ number_format($laba_rugi->modal) }}</span>
      <span class="label label-warning">LABA : Rp {{ number_format($laba_rugi->laba) }}</span>
    </h4>
    <hr>

    <table class="table table-hover table-condensed table-bordered" id="labarugi">
      <thead>
        <tr class="info">
          <th style="width: 10%"><center>Waktu</center></th>
          <th style="width: 12%"><center>No Nota</center></th>
          <th><center>Member</center></th>
          <th><center>Kasir</center></th>
          <th style="width: 13%"><center>Omset</center></th>
          <th style="width: 13%"><center>Modal</center></th>
          <th style="width: 13%"><center>Laba</center></th>
        </tr>
      </thead>
      <tbody>
        <?php 
          $omset = 0;  
          $modal = 0;
          $laba = 0;
        ?>
        @foreach($penjualan as $tranksaksi)
        <tr>
          <td><center>{{ App\Http\Controllers\LibraryController::timestampsToHour($tranksaksi->created_at) }} WIB</center></td>
          <td><center><a href="{{ route('nota.detail', $tranksaksi->nota_id) }}">{{ $tranksaksi->nota_id }}</a></center></td>
          <td><center>{{ $tranksaksi->nama_member }}</center></td>
          <td><center>{{ $tranksaksi->name }}</center></td>
          <td><center>Rp {{ number_format($tranksaksi->total) }}</center></td>
          <td><center>Rp {{ number_format($tranksaksi->modal) }}</center></td>
          <td><center>Rp {{ number_format($tranksaksi->laba) }}</center></td>
        </tr>
        <?php 
          $omset = $omset + $tranksaksi->total;
          $modal = $modal + $tranksaksi->modal;
          $laba = $laba + $tranksaksi->laba;

        ?>
        @endforeach
        <tfoot>
          <tr class="info">
            <td colspan="4"><center><strong>Total</strong></center></td>
            <td><center>Rp {{ number_format($omset) }}</center></td>
            <td><center>Rp {{ number_format($modal) }}</center></td>
            <td><center>Rp {{ number_format($laba) }}</center></td>
          </tr>
        </tfoot>
      </tbody>
    </table>

  </div>
</div>
@endsection

@section('custom_scripts')
<script type="text/javascript">
  $(function() {
    $("#labarugi").dataTable();
  });
</script>
@endsection