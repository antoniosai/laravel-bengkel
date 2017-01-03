@extends('layouts.main')

@section('title')
Menu Utama
@endsection

@section('content')
@include('partials.navbar')
<div class="well">
  <h4>Point Of Sales (POS)
    <div class="pull-right">
      <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#tukarPoin">Tukar Poin</button>
      <a href="{{ url('admin/pos/') }}" class="btn btn-success btn-xs">Buat POS Baru</a>
    </div>
  </h4>
</div>
@include('partials.alert')
@include('partials.warning')
<ul class="nav nav-tabs" id="myTab">
  <li class="active"><a data-target="#pendingSales" data-toggle="tab">Pending Sales</a></li>
</ul>

<div class="tab-content">
  <div class="tab-pane active" id="pendingSales">
    <br>
    <table class="table table-hover table-striped" id="pos">
      <thead>
        <tr class="info">
          <th style="width: 150px"><center>Tanggal</center></th>
          <th style="width: 150px"><center>No Nota</center></th>
          <th style="width: 150px"><center>Member</center></th>
          <th style="width: 150px"><center>Total Belanja</center></th>
          <th style="width: 90px"><center>Aksi</center></th>
        </tr>
      </thead>
      <tbody>
        @foreach($order as $list)
        <tr>
          <td><center>{{ App\Http\Controllers\LibraryController::waktuIndonesiaWithSecond($list->created_at) }}</center></td>
          <td><center>{{ $list->nota_id }}</center></td>
          <td><center>{{ $list->nama_member }}</center></td>
          <td><center>Rp. {{ number_format($list->total) }}</center></td>
          <td>
            <center><a href="{{ url('admin/pos/'.$list->nota_id.'/'.$list->id) }}" class="btn btn-warning btn-xs">Buka</a>
            <a href="{{ route('nota.delete', $list->nota_id) }}" class="btn btn-danger btn-xs">Hapus</a></center>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@include('partials.form._addbarang')
@include('partials.form._addmember')


<!-- Modal Tukar Poin -->
<div class="modal fade" id="tukarPoin" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tukar Poin</h4>
      </div>
      @if(!count($member) == 0)
        <div class="modal-body">
          <form  action="{{ action('PosController@tukarPoin') }}" method="POST">
            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
            {{ csrf_field() }}
            <div class="form-group">
              <label for="member_id">Pilih Member</label>
              <select class="js-selectize" id="member_id" name="member_id">
                <option value="" selected="selected"></option>
                @foreach($member as $anggota)
                  @if($anggota->nama_member == 'Guest')
                  @endif
                <option {{ $class = 'yes'}} value="{{ $anggota->id }}">{{ $anggota->nama_member }} ---- Sisa Poin : {{ $anggota->poin }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="barang_id">Pilih Barang</label>
              <select class="js-selectize" id="barang_id" name="barang_id">
                <option value="" selected="selected"></option>
                @foreach($barang as $listBarang)
                <!-- <li><a href="#">{{ $anggota->nama_member }}</a></li> -->
                <option value="{{ $listBarang->id }}">{{ $listBarang->nama_barang }} | Poin : {{ $listBarang->bobot_poin }} | Stok : {{ $listBarang->stok }}</option>
                @endforeach
              </select>
            </div>
            <div class="clearfix">
              <div class="pull-right">
                <button type="submit" class="btn btn-success btn-sm">Pilih</button>
              </div>
            </div>
          </form>
        </div>
      @else
        <center><h2>Belum ada Member</h2></center>
        <br>
      @endif
    </div>
  </div>
</div>
<!-- End Modal  Tukar Poin --> 

@endsection

@section('custom_scripts')
<script type="text/javascript">
  $(document).ready(function() {
    $('.js-selectize').selectize({
      sortField: 'text'
    });

    $("#pos").dataTable();
  });
</script>
@endsection
