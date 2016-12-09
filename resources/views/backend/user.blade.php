@extends('layouts.main')

@section('title')
  Managemen User
@endsection

@section('content')
@include('partials.navbar')
@include('partials.alert')
<ul class="nav nav-tabs" id="myTab">
  <li class="active"><a data-target="#listUser" data-toggle="tab">List User</a></li>
  <li><a data-target="#role" data-toggle="tab">Manajemen Role</a></li>
</ul>

<div class="tab-content">
  <div class="tab-pane active" id="listUser">
    <div class="row">
      <div class="col-md-12">
        <h3>Daftar User
          <div class="pull-right">
            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#tambahUser">Tambah User</button>
            <a href="{{ action('ExportController@barangMasukToPdf') }}" class="btn btn-sm btn-success">Cetak PDF</a>
          </div>
        </h3>
        <hr>
        <table class="table table-bordered table-stripped table-hover" id="user">
          <thead>
            <tr>
              <th><center>Nama</center></th>
              <th><center>Username</center></th>
              <th><center>Email</center></th>
              <th><center>Role</center></th>
              <th style="width: 220px"><center>Aksi</center></th>
            </tr>
          </thead>
          <tbody>
            @foreach($user as $listUser)
            <tr>
              <td><center>{{ $listUser->name }}</center></td>
              <td><center>{{ $listUser->username }}</center></td>
              <td><center>{{ $listUser->email }}</center></td>
              <td>
                <center><span class="label label-success label-lg">{{ $listUser->display_name }}</span></center>
              </td>
              <td>
                <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#stokBarang{{$listUser->id}}">Tambah Stok</button>
                <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#editUser{{$listUser->id}}">Edit Barang</button>
                @include('partials.form._edituser')
                <a href="#" class="btn btn-danger btn-xs">Hapus</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="tab-pane" id="role">
    <div class="row">
      <div class="col-md-8">
        <h3>Daftar Grup</h3>
        <hr>
        <table class="table table-bordered table-stripped table-hover" id="group">
          <thead>
            <tr>
              <th style="width: 40px">No</th>
              <th style="width: 250px"><center>Nama Group</center></th>
              <th><center>Hak Akses</center></th>
              <th style="width: 100px">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1 ?>
            @foreach($role as $listRole)
            <tr>
              <td>{{ $no++ }}</td>
              <td><center>{{ $listRole->display_name }}</center></td>
              <td></td>
              <td>
                <a href="#" class="btn btn-xs btn-warning">Edit</a>
                <a href="#" class="btn btn-xs btn-danger">Delete</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="col-md-4">
        <h3>Daftar Hak Akses</h3>
        <hr>
        <ul>
          @foreach($permission as $hak_akses)
          <li>
            <b>{{ $hak_akses->display_name }}</b>
            <p>
              {{ $hak_akses->description }}
            </p>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>



@include('partials.form._adduser')

@endsection

@section('custom_scripts')
<script type="text/javascript">
  $(function() {
    $("#user").dataTable();

    $("#group").dataTable();
  });
</script>
@endsection
