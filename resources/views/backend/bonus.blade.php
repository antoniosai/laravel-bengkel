@extends('layouts.main')

@section('title')
  Management Member
@endsection

@section('content')
@include('partials.navbar')
<div class="well">
  <h3>Setting Diskon & Poin
  </h3>
</div>

@include('partials.alert')
@include('partials.warning')

<div class="row">
  <div class="col-md-6">
    <h3>Diskon
      <div class="pull-right">
        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#tambahDiskon">Tambah Diskon</button>
      </div>
    </h3>
    <hr>
    <table class="table table-stripped table-bordered table-hover">
      <thead>
        <tr>
          <th>Jika Belanja Lebih</th>
          <th>Diskon</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @if(count($diskon) == 0)
          <tr>
            <td colspan="3"><center><h3>Belum Konfigurasi Diskon</h3></center></td>
          </tr>
        @else
          @foreach($diskon as $listDiskon)
          <tr>
            <td>Rp {{ number_format($listDiskon->harga_belanja) }}</td>
            <td>{{ $listDiskon->diskon }}%</td>
            <td>
              <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#detailDiskon{{$listDiskon->id}}">Edit</button>
              <a href="{{ route('delete.diskon', $listDiskon->id) }}" class="btn btn-danger btn-xs">Hapus</a>
            </td>
          </tr>
          @endforeach
        @endif
      </tbody>
    </table>

  </div>
  <div class="col-md-6">
    <h3>Poin
      <div class="pull-right">
        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#tambahPoin">Tambah Poin</button>
      </div>
    </h3>
    <hr>
    <table class="table table-stripped table-bordered table-hover">
      <thead>
        <tr>
          <th>Jika Belanja Lebih</th>
          <th>Poin</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @if(count($poin) == 0)
          <tr>
            <td colspan="3"><center><h3>Belum Konfigurasi Poin</h3></center></td>
          </tr>
        @else
          @foreach($poin as $listPoin)
          <tr>
            <td>Rp {{ number_format($listPoin->harga_belanja) }}</td>
            <td>+{{ $listPoin->poin }}</td>
            <td>
              <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#detailPoin{{$listPoin->id}}">Edit</button>
              <a href="{{ route('delete.poin', $listPoin->id) }}" class="btn btn-danger btn-xs">Hapus</a>
            </td>
          </tr>
          @endforeach
        @endif
      </tbody>
    </table>
  </div>
</div>
@include('partials.form._adddiskon')
@include('partials.form._addpoin')

<!-- Start Modal Detail Diskon -->
@foreach($diskon as $listDiskon)
<div class="modal fade" id="detailDiskon{{$listDiskon->id}}" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Diskon</h4>
      </div>
      <div class="modal-body">
        <form action="{{ action('BonusController@postSaveDiskon')}}" method="post">
          <input type="hidden" name="id" value="{{ $listDiskon->id }}">
          {{ csrf_field() }}
          <div class="form-group">
            <label>Jika Belanja Lebih dari</label>
            <div class="input-group">
              <span class="input-group-addon">Rp</span>
              <input type="text" name="harga_belanja" placeholder="Masukan Harga Belanja" value="{{ $listDiskon->harga_belanja }}" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label>Diskon</label>
            <input type="text" name="diskon" placeholder="Masukan Diskon" value="{{ $listDiskon->diskon }}" class="form-control">
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-info">Simpan Diskon</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endforeach
<!-- End Modal Detail Diskon-->

<!-- Start Modal Detail Poin -->
@foreach($poin as $listPoin)
<div class="modal fade" id="detailPoin{{$listPoin->id}}" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Poin</h4>
      </div>
      <div class="modal-body">
        <form action="{{ action('BonusController@postSavePoin')}}" method="post">
          <input type="hidden" name="id" value="{{ $listPoin->id }}">
          {{ csrf_field() }}
          <div class="form-group">
            <label>Jika Belanja Lebih dari</label>
            <div class="input-group">
              <span class="input-group-addon">Rp</span>
              <input type="text" name="harga_belanja" placeholder="Masukan Harga Belanja" value="{{ $listPoin->harga_belanja }}" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label>Poins</label>
            <input type="text" name="diskon" placeholder="Masukan Poin" value="{{ $listPoin->poin }}" class="form-control">
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-info">Simpan Poin</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endforeach
<!-- End Modal Detail Poin-->
@endsection
