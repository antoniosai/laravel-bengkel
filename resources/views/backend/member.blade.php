@extends('layouts.main')

@section('title')
  Management Member
@endsection

@section('content')
@include('partials.navbar')
<div class="well">
  <h3>Manajemen Member
    <div class="pull-right">
      <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#tambahMember">Tambah Member</button>
    </div>
  </h3>
</div>
@include('partials.alert')
<table class="table table-stripped table-bordered table-hover" id="member">
  <thead>
    <tr>
      <th>No</th>
      <th>Nama Member</th>
      <th>Poin</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; ?>
    @foreach($member as $listMember)
    <tr>
      <td>{{ $no++ }}</td>
      <td>{{ $listMember->nama_member }}</td>
      <td>
        @if(!$listMember->poin)
        -
        @else
        {{ $listMember->poin }}
        @endif
      </td>
      <td>
        <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#detailMember1">Detail Member</button>
        <a href="#" class="btn btn-danger btn-xs">Hapus</a>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>


@include('partials.form._addmember')

<!-- Modal Detail Member -->
<div class="modal fade" id="detailMember1" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Member Antonio Saiful Islam</h4>
        <hr>
        <p>
          Handphone : <b>0812123412312</b> | Poin : <b>20</b>
        </p>
        <a href="#" class="btn btn-xs btn-success">Klik Untuk Melihat Detail</a>
      </div>
      <div class="modal-body">

        <ul class="nav nav-tabs" id="myTab">
          <li class="active"><a data-target="#aktivitas" data-toggle="tab">Aktivitas</a></li>
          <li><a data-target="#editProfile" data-toggle="tab">Edit Profile</a></li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane active" id="aktivitas">
            <br>
            <table class="table table-striped table-hover table-bordered">
              <thead>
                <tr>
                  <th>Nama Barang</th>
                  <th style="width: 90px">Qty</th>
                  <th>Harga</th>
                  <th>Tanggal</th>
                </tr>
              </thead>
              <tbody>
                  <tr>
                    <td>Oli Reposol</td>
                    <td>2</td>
                    <td>Rp. 50.000</td>
                    <td>28 November 2016</td>
                  </tr>
                  <tr>
                    <td>Oli Reposol</td>
                    <td>2</td>
                    <td>Rp. 50.000</td>
                    <td>28 November 2016</td>
                  </tr>
              </tbody>
            </table>
          </div>

          <div class="tab-pane" id="editProfile">
            <br>
            <form action="" method="post">
              {{ csrf_field() }}
              <div class="form-group">
                <label for="">Nama Member</label>
                <input type="text" name="nama_member" value="Antonio Saiful" class="form-control">
              </div>
              <div class="form-group">
                <label for="">Nomor Handphone</label>
                <input type="text" name="handphone" value="08121494007" class="form-control">
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<!-- Modal Detail Member -->


@endsection

@section('custom_scripts')
<script type="text/javascript">
  $(function() {
    $("#member").dataTable();
  });
</script>
@endsection
