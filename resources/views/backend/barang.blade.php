@extends('layouts.main')

@section('title')
  Daftar Barang
@endsection

@section('content')
@include('partials.navbar')
<div class="well">
  <h3>Managemen Barang
    <div class="pull-right">
      <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#importExcel">Import Barang (Excel)</button>
      <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#tambahBarang">Tambah Barang</button>
    </div>
  </h3>
</div>
@include('partials.alert')
@include('partials.warning')
@include('partials.validationmessage')

<table class="table table-bordered table-stripped table-hover" id="barang">
  <thead>
    <tr class="info">
      <th><center>No<br>&nbsp;</center></th>
      <th style="width: 200px"><center>Nama Barang<br>&nbsp;</center></th>
      <th><center>Stok<br>&nbsp;</center></th>
      <th><center>Harga<br>Pokok</center></th>
      <th><center>Harga<br>Umum</center></th>
      <th><center>Harga<br>Khusus</center></th>
      <th><center>Bobot<br>Poin</center></th>
      <th><center>Opsi<br>Tukar Poin</center></th>
      <th style="width: 90px"><center>Aksi<br>&nbsp;</center></th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; ?>
    @foreach($barang as $listBarang)
    <tr>
      <td><center>{{ $no++ }}</center></td>
      <td>{{ $listBarang->nama_barang }}</td>
      <td><center>{{ $listBarang->stok }}</center></td>
      <td><center>Rp. {{ number_format($listBarang->harga) }}</center></td>
      <td><center>Rp. {{ number_format($listBarang->harga_jual) }}</center></td>
      <td>
        @if($listBarang->harga_khusus == null)
          <center>-</center>
        @else
          <center>Rp. {{ number_format($listBarang->harga_khusus) }}</center>
        @endif
      </td>
      <td style="text-align: center">{{ $listBarang->bobot_poin }}</td>
      <td>
        @if($listBarang->opsi_tukarpoin == 'yes')
          <?php
            $spanClass = 'success';
            $name = 'Yes';
          ?>
        @else
          <?php
            $spanClass = 'danger';
            $name = 'No';
          ?>
        @endif
        <center><span class="label label-{{ $spanClass }}">{{ $name }}</span></center>
      </td>
      <td>
        <center>
          <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#stokBarang{{$listBarang->id}}"><i class="fa fa-plus fa-lg"></i></button>
        <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#editBarang{{$listBarang->id}}"><i class="fa fa-pencil fa-lg"></i></button>
        <a href="{{ route('barang.delete', [$listBarang->id]) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash fa-lg"></i></a>

        </center>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

@include('partials.form._addbarang')

<!-- Start Barang Modal By Id -->
@foreach($barang as $barangs)
<div class="modal fade" id="editBarang{{$barangs->id}}" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Barang : {{$barangs->nama_barang}}</h4>
      </div>
      <div class="modal-body">
        <form action="{{ action('BarangController@postEditBarang')}}" method="post">
          {{ csrf_field() }}
          <input type="hidden" name="id" value="{{ $barangs->id }}">
          <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" value="{{ $barangs->nama_barang }}" placeholder="Masukkan Nama Barang" class="form-control">
          </div>
          <div class="form-group">
            <label>Stok Awal</label>
            <input type="number" name="stok" value="{{ $barangs->stok }}" placeholder="Stok Awal" class="form-control">
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Harga</label>
                <input type="number" name="harga" value="{{ $barangs->harga }}" placeholder="Masukkan Harga" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Harga Jual</label>
                <input type="number" name="harga_jual" value="{{ $barangs->harga_jual }}" placeholder="Masukkan Harga Jual" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Harga Jual</label>
                <input type="number" name="harga_khusus" value="{{ $barangs->harga_khusus }}" placeholder="Masukkan Harga Khusus" class="form-control">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Nilai Tukar Poin</label>
            <input type="number" name="bobot_poin" value="{{ $barangs->bobot_poin }}" placeholder="Masukkan Poin" class="form-control">
          </div>
          <div class="form-group">
            <label>Opsi Tukar Poin</label>
            <br>

            @if($barangs->opsi_tukarpoin == 'yes')
              <label class="radio-inline">
                <input checked="checked" type="radio" name="opsi_tukarpoin" value="yes"> <span class="label label-success">Ya</span>
              </label>
              <label class="radio-inline">
                <input type="radio" name="opsi_tukarpoin" value="no"> <span class="label label-danger">No</span>
              </label>
            @else
              <label class="radio-inline">
                <input type="radio" name="opsi_tukarpoin" value="yes"> <span class="label label-success">Ya</span>
              </label>
              <label class="radio-inline">
                <input checked="checked" type="radio" name="opsi_tukarpoin" value="no"> <span class="label label-danger">No</span>
              </label>
            @endif
          </div>
          <hr>
          <div class="form-group clearfix">
            <button type="submit" class="btn btn-info pull-right">Simpan Barang</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endforeach
<!-- End Barang Modal By Id -->

<!-- Start Barang Modal By Id -->
<div class="modal fade" id="importExcel" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import From Excel
          <a href="{{ action('BarangController@generateExcelTemplate') }}" class="btn btn-xs btn-info">Download Template Terbaru</a>
        </h4>
      </div>
      <div class="modal-body">
        <form action="{{ action('BarangController@importBarang')}}" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="form-group">
            <label>Pilih File</label>
            <input type="file" name="excel" placeholder="Pilih File .xlsx .xls" class="form-control">
          </div>
          <div class="clearfix">
            <button type="submit" name="button" class="pull-right btn btn-success">Upload</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
<!-- End Barang Modal By Id -->


<!-- Start Barang Modal By Id -->
@foreach($barang as $barangs)
<div class="modal fade" id="stokBarang{{$barangs->id}}" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Stok Barang</h4>
      </div>
      <div class="modal-body">
        <form action="{{ action('BarangController@tambahStok')}}" method="post">
          {{ csrf_field() }}
          <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
          <input type="hidden" name="id" value="{{ $barangs->id }}">
          <div class="form-group">
            <label>Nama Barang</label>
            <input required type="text" disabled name="nama_barang" placeholder="Masukkan Nama Barang" value="{{ $barangs->nama_barang }}" class="form-control">
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Stok Terakhir</label>
                <input type="text" disabled placeholder="Masukkan Stok" value="{{ $barangs->stok }}" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Stok Tambahan</label>
                <input type="number" name="stok_tambahan" placeholder="Masukkan Stok Tambahan"  class="form-control">
              </div>
            </div>
          </div>
          <hr>
          <div class="form-group">
            <button type="submit" class="btn btn-info">Simpan Barang</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endforeach
<!-- End Barang Modal By Id -->

@endsection

@section('custom_scripts')
<script type="text/javascript">
  $(function() {
    $("#barang").dataTable();
  });
</script>
@endsection
