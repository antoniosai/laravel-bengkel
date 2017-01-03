@extends('layouts.main')

@section('title')
  Management Member
@endsection

@section('custom_styles')
  <style type="text/css">
    .seperator-table {
      width: 20px;
    }

  </style>
@endsection

@section('content')
@include('partials.navbar')
<div class="well">
  <h3>Manajemen Member
    <div class="pull-right">
      <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#tambahMember"><i class="fa fa-plus fa-lg"></i> Tambah Member</button>
    </div>
  </h3>
</div>
@include('partials.warning')
@include('partials.alert')
@include('partials.validationmessage')
<table class="table table-stripped table-bordered table-hover" id="member">
  <thead>
    <tr class="info">
      <th style="width: 30px"><center>No</center></th>
      <th style="width: 290px"><center>Nama Member</center></th>
      <th><center>Alamat</center></th>
      <th style="width: 80px"><center>Poin</center></th>
      <th style="width: 120px"><center>Aksi</center></th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; ?>
    @foreach($member as $listMember)
    <tr>
      <td><center>{{ $no++ }}</center></td>
      <td><center>{{ $listMember->nama_member }}</center></td>
      <td><center>{{ $listMember->alamat }}</center></td>
      <td>
        <center>
        @if(!$listMember->poin)
        -
        @else
        {{ $listMember->poin }}
        @endif
        </center>
      </td>      
      <td>
        <center>
          <a href="{{ route('report.member', $listMember->id) }}" class="btn btn-warning btn-xs"><i class="fa fa-user fa-lg"></i> Detail</a>
          <a href="{{ route('member.delete', $listMember->id) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash fa-lg"></i> Hapus</a>

        </center>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>


<?php 

function tranksaksiQuery($member_id){
  $aktivitasTranksaksiQuery = "
    SELECT tranksaksis.nota_id, tranksaksis.qty,tranksaksis.subtotal, tranksaksis.total, tranksaksis.created_at, barangs.nama_barang
    FROM barangs, tranksaksis, members
    WHERE tranksaksis.member_id = members.id
    AND tranksaksis.barang_id = barangs.id
    AND tranksaksis.member_id = $member_id
    ORDER BY tranksaksis.created_at DESC
    LIMIT 5
  ";

  return $tranksaksi = DB::select(DB::raw($aktivitasTranksaksiQuery));
}

function tukarPoinQuery($member_id){
  $queryTukarPoin = "
    SELECT hadiahs.nama_barang, members.nama_member, users.name, tukar_poins.created_at
    FROM hadiahs, members, users, tukar_poins
    WHERE tukar_poins.barang_id = hadiahs.id
    AND tukar_poins.member_id = members.id
    AND tukar_poins.user_id = users.id
    AND tukar_poins.member_id = $member_id
    ORDER BY tukar_poins.created_at DESC
    LIMIT 5
  ";

  return $tukarpoin = DB::select(DB::raw($queryTukarPoin));
}

?>


@include('partials.form._addmember')

<!-- Modal Detail Member -->
@foreach($member as $detailMember)

<div class="modal fade" id="detailMember{{ $detailMember->id }}" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Member {{ $detailMember->nama_member }}</h4>
        <hr>
        <table>
          <tr>
            <td><strong>Nama</strong></td>
            <td class="seperator-table"></td>
            <td class="seperator-table">:</td>
            <td>{{ $detailMember->nama_member }}</td>
          </tr>
          <tr>
            <td><strong>Poin Terkumpul</strong></td>
            <td class="seperator-table"></td>
            <td class="seperator-table">:</td>
            <td>{{ $detailMember->poin }}</td>
          </tr>
          <tr>
            <td><strong>Alamat</strong></td>
            <td class="seperator-table"></td>
            <td class="seperator-table">:</td>
            <td>{{ $detailMember->alamat }}</td>
          </tr>
          <tr>
            <td><strong>Tanggal Daftar</strong></td>
            <td class="seperator-table"></td>
            <td class="seperator-table">:</td>
            <td>{{ App\Http\Controllers\LibraryController::waktuIndonesia($detailMember->created_at) }}</td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td><a href="{{ route('report.member', $detailMember->id) }}" class="btn btn-xs btn-success">Lihat Detail</a></td>
          </tr>
        </table>
      </div>
      <div class="modal-body">

        <ul class="nav nav-tabs" id="myTab">
          <li class="active"><a data-target="#aktivitas" data-toggle="tab">Tranksksi</a></li>
          <li><a data-target="#poin" data-toggle="tab">Penukaran Poin</a></li>
          <li><a data-target="#editProfile" data-toggle="tab">Edit Profile</a></li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane active" id="aktivitas">
            <br>
            <table class="table table-striped table-hover table-bordered table-condensed">
              <thead>
                <tr class="success">
                  <th style="width: 170px"><center>Tanggal</center></th>
                  <th style="width: 120px"><center>Total Belanja</center></th>
                  <th><center>Kasir</center></th>
                  <th style="width: 60px"></th>
                </tr>
              </thead>
              <tbody>
                @forelse($detailMember->tranksaksi as $activity)
                  <tr>
                    <td><center>{{ App\Http\Controllers\LibraryController::waktuIndonesia($activity->created_at) }}</center></td>
                    <td><center>Rp {{ number_format($activity->total) }}</center></td>
                    <td><center>{{ $activity->nota_id }}</center></td>
                    <td><a href="{{ route('nota.detail', $activity->nota_id) }}" class="btn btn-xs btn-info">Detail</a></td>
                  </tr>
                @empty
                  <center><h4>Tidak Ada Data Tranksaksi</h4></center>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="tab-pane" id="editProfile">
            <br>
            <form action="{{ action('MemberController@postEditMember') }}" method="post">
              {{ csrf_field() }}
              <input type="hidden" name="id" value="{{ $detailMember->id }}">

              <div class="form-group">
                <label for="">Nama Member</label>
                <input type="text" name="nama_member" value="{{ $detailMember->nama_member }}" class="form-control">
              </div>
              <div class="form-group">
                <label for="">Nomor Handphone</label>
                <input type="text" name="handphone" value="{{ $detailMember->handphone }}" class="form-control">
              </div>
              <div class="form-group">
                <label for="">Alamat</label>
                <textarea class="form-control" name="alamat">{{ $detailMember->alamat }}</textarea>
              </div>
              <div class="clearfix">
                <button type="submit" class="pull-right btn btn-info">Simpan</button>
              </div>
            </form>
          </div>

          <div class="tab-pane" id="poin">
          <br>
            <table class="table table-hover table-striped table-bordered table-condensed" id="poin">
              <thead>
                <tr class="success">
                  <th><center>Tanggal Penukaran</center></th>
                  <th><center>Operator</center></th>
                  <th><center>Barang</center></th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1 ?>
                @foreach($detailMember->tukarpoin as $listPoin)
                <tr>
                  <td><center>{{ App\Http\Controllers\LibraryController::waktuIndonesia($listPoin->created_at) }}</center></td>
                  <td><center>{{ App\User::find($listPoin->user_id)->name }}</center></td>
                  <td><center>{{ App\Hadiah::find($listPoin->barang_id)->nama_barang }}</center></td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endforeach
<!-- Modal Detail Member -->


@endsection

@section('custom_scripts')
<script type="text/javascript">
  $(function() {
    $("#member").dataTable();
  });
</script>
@endsection
