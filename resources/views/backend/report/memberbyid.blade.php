@extends('layouts.main')

@section('title')
Laporan Barang
@endsection

@section('custom_styles')

@endsection

@section('content')
<div class="well">
  <center>
    <div class="row" style="margin-top: -40px; margin-bottom: -15px">
      <div class="col-md-3">
        <h3>
          <small>Nama Member</small><br>
          <i>{{ $member->nama_member }}</i>
        </h3>
      </div>
      <div class="col-md-2">
        <h3>
          <small>Poin Terkumpul</small><br>
          <i>
            @if($member->poin == "")
              {{ 0 }}
            @else
              {{ $member->poin }}
            @endif
          </i>
        </h3>
      </div>
      <div class="col-md-2">
        <h3>
          <small>Sisa Poin</small><br>
          <i>
            @if($member->poin == "")
              {{ 0 }}
            @else
              {{ $member->sisa_poin }}
            @endif
          </i>
        </h3>
      </div>
      <div class="col-md-2">
        <h3>
          <small>Total Tranksaksi</small><br>
          <i>{{ count($member->tranksaksi) }}</i>
        </h3>
      </div>
      <div class="col-md-3">
        <h3>
          <small>Tanggal Daftar</small><br>
          <i>{{ App\Http\Controllers\LibraryController::waktuIndonesia($member->created_at) }}</i>
        </h3>
      </div>

    </div>
  </center>
</div>

<ul class="nav nav-tabs" id="myTab">
  <li class="active"><a data-target="#aktivitasTranksaksi" data-toggle="tab">Tranksaksi</a></li>
  <li><a data-target="#aktivitasTukarPoin" data-toggle="tab">Penukaran Poin</a></li>
</ul>

<div class="tab-content">
  <div class="tab-pane active" id="aktivitasTranksaksi">
    <br>
    <table class="table table-hover table-bordered table-condensed" id="tranksaksi">
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
        @forelse($tranksaksi as $listTranksaksi)
        <tr>
          <td><center>{{ App\Http\Controllers\LibraryController::waktuIndonesiaWithSecond($listTranksaksi->created_at) }}</center></td>
          <td><center>{{ $listTranksaksi->nota_id }}</center></td>
          <td><center>{{ $listTranksaksi->faktur_id }}</center></td>
          <td><center>{{ $listTranksaksi->nama_member }}</center></td>
          <td><center>Rp {{ number_format($listTranksaksi->subtotal) }}</center></td>
          <td><center>{{ $listTranksaksi->name }}</center></td>
          <td><center><a href="{{ route('nota.detail', $listTranksaksi->nota_id) }}" class="btn btn-xs btn-info">Detail</a></center></td>
        </tr>
        @empty
        <tr>
          <td></td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="tab-pane" id="aktivitasTukarPoin">
    <br>
    <table class="table table-hover table-striped table-bordered table-condensed">
      <thead>
        <tr class="info">
          <th style="width: 180px"><center>Tanggal Penukaran</center></th>
          <th><center>Nama Member</center></th>
          <th><center>Barang</center></th>
          <th><center>Poin</center></th>
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
          <td><center>-{{ $listPoin->poin }}</center></td>
          <td><center>{{ $listPoin->name }}</center></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

</div>

@endsection

@section('custom_scripts')
<script type="text/javascript">
$(document).ready(function() {
  $('#tranksaksi').DataTable();
  $('#poin').DataTable();
});
</script>

@endsection
