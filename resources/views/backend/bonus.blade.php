@extends('layouts.main')

@section('title')
  Management Member
@endsection

@section('content')
<div class="well">
  <h3>Setting Diskon & Poin
  </h3>
</div>

<div class="row">
  <div class="col-md-6">
    <h3>Poin
      <div class="pull-right">
        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#tambahPoin">Tambah Poin</button>
      </div>
    </h3>
    <hr>
    <table class="table table-striped table-hover table-condensed">
      <thead>
        <tr class="info">
          <th><center>Level</center></th>
          <th><center>Jika Belanja Lebih</center></th>
          <th><center>Poin</center></th>
          <th style="width: 90px"><center>Aksi</center></th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1; ?>
        @if(count($poin) == 0)
          <tr>
            <td colspan="4"><center><h3>Belum Konfigurasi Poin</h3></center></td>
          </tr>
        @else
          @foreach($poin as $listPoin)
          <tr>
            <td><center>Level {{ $no++ }}</center></td>
            <td><center>Rp {{ $listPoin->harga_belanja }}</center></td>
            <td><center>+{{ $listPoin->poin }}</center></td>
            <td>
              <center>
                <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#detailPoin{{$listPoin->id}}"><i class="fa fa-pencil fa-lg"></i></button>
              <a href="{{ route('delete.poin', $listPoin->id) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash fa-lg"></i></a>
              </center>
            </td>
          </tr>
          @endforeach
        @endif
      </tbody>
    </table>

    <p>
      <h4><small>*) Poin tambahan hanya berlaku pada member Reguler</small></h4>
    </p>

  </div>
  <div class="col-md-6">
    <h3>
      Barang Untuk Penukaran Poin
      <div class="pull-right">
        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#tambahBarang">Tambah Poin</button>
      </div>
    </h3>
    <hr>
    <table class="table table-striped table-condensed">
      <thead>
        <tr class="info">
          <th><center>No</center></th>
          <th><center>Nama Barang</center></th>
          <th><center>Bobot Poin</center></th>
          <th><center>Stok</center></th>
          <th><center>Aksi</center></th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1; ?>
        @forelse($hadiah as $barangs)
        <tr>
          <td><center>{{ $no++ }}</center></td>
          <td><center>{{ $barangs->nama_barang }}</center></td>
          <td><center>{{ $barangs->bobot_poin }}</center></td>
          <td><center>{{ $barangs->stok }}</center></td>
          <td>
            <center>
              <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#detailHadiah{{$barangs->id}}"><i class="fa fa-pencil fa-lg"></i></button>
              <a href="{{ route('delete.hadiah', $barangs->id) }}" class="btn btn-danger btn-xs"><span class="fa fa-trash fa-lg"></span></a>
            </center>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="4"><center><h3>Tidak ada Data Barang</h3></center></td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@include('partials.form._addpoin')
@include('partials.form._addhadiah')

<!-- Start Modal Detail Poin -->
@foreach($poin as $listPoin)
<div class="modal fade" id="detailPoin{{$listPoin->id}}" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Poinss</h4>
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
            <label>Poin</label>
            <input type="text" name="poin" placeholder="Masukan Poin" value="{{ $listPoin->poin }}" class="form-control">
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


@foreach($hadiah as $barang)
<div class="modal fade" id="detailHadiah{{$barang->id}}" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Hadiah {{ $barang->nama_barang }}</h4>
      </div>
      <div class="modal-body">
        <form action="{{ action('BonusController@postEditHadiah')}}" method="post">
          {{ csrf_field() }}
          <input type="hidden" name="id" value="{{ $barang->id }}">
          <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" placeholder="Masukan Nama Barang" value="{{ $barang->nama_barang }}" class="form-control">
          </div>
          <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" placeholder="Masukan Stok Tersedia" value="{{ $barang->stok }}" class="form-control">
          </div>
          <div class="form-group">
            <label>Bobot Poin</label>
            <input type="number" name="bobot_poin" placeholder="Masukan Bobot Poin" value="{{ $barang->bobot_poin }}" class="form-control">
          </div>
          <div class="form-group">
            <div class="clearfix">
              <div class="pull-right">
                <button type="submit" class="btn btn-info">Simpan Hadiah</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endforeach

@endsection
