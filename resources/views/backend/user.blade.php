@extends('layouts.main')

@section('title')
  Managemen User
@endsection

@section('content')
<ul class="nav nav-tabs" id="myTab">
  <li class="active"><a data-target="#listUser" data-toggle="tab">List User</a></li>
  <li><a data-target="#role" data-toggle="tab">Hak Akses</a></li>
</ul>
<div class="tab-content">
  <div class="tab-pane active" id="listUser">
    <div class="row">
      <div class="col-md-12">
        <h3>Daftar User
          <div class="pull-right">
            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#tambahUser"><i class="fa fa-user-plus fa-lg"></i> &nbsp;Tambah User</button>
          </div>
        </h3>
        <hr>
        <table class="table table-striped table-hover table-condensed" id="user">
          <thead>
            <tr class="info">
              <th style="width: 20%"><center>Nama</center></th>
              <th style="width: 15%"><center>Username</center></th>
              <th style="width: 20%"><center>Email</center></th>
              <th style="width: 35%"><center>Hak Akses</center></th>
              <th style="width: 10%"><center>Aksi</center></th>
            </tr>
          </thead>
          <tbody>
            @foreach($user as $listUser)
            <tr>
              <td><center>{{ $listUser->name }}</center></td>
              <td><center>{{ $listUser->username }}</center></td>
              <td><center>{{ $listUser->email }}</center></td>
              <?php 
                $aksesQuery = "
                  SELECT permissions.display_name
                  FROM permissions, users, user_has_permissions
                  WHERE user_has_permissions.user_id = users.id
                  AND user_has_permissions.permission_id = permissions.id                  
                  AND user_has_permissions.user_id = $listUser->id
                ";

                $akses = DB::select(DB::raw($aksesQuery));
              ?>
              <td>
                <center>
                  @forelse($akses as $permission)
                    <span class="label label-primary label-lg">{{ $permission->display_name }}</span>
                  @empty
                    <span class="label label-danger label-lg">Tidak memiliki hak akses</span>
                  @endforelse
                </center>
              </td>
              <td>
                <center>
                  <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#edit{{ $listUser->id }}"><i class="fa fa-pencil fa-lg"></i></button>
                  <a href="{{ route('user.delete', $listUser->id) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash fa-lg"></i></a>
                </center>
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
        <h3>Daftar Hak Akses</h3>
        <hr>
        <table class="table table-stripped table-hover table-condensed">
          <thead>
            <tr class="info">
              <th style="width: 40px"><center>No</center></th>
              <th style="width: 250px"><center><center>Nama Akses</center></center></th>
              <th><center>Keterangan</center></th>
              <th style="width: 60px"><center></center></th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1;
              $permissionQuery = "
                SELECT * FROM permissions
              ";

              $permission = DB::select(DB::raw($permissionQuery));
            ?>
            @foreach($permission as $akses)
            <tr>
              <td><center>{{ $no++ }}</center></td>
              <td><center>{{ $akses->display_name }}</center></td>
              <td><center>{{ $akses->description }}</center></td>
              <td>
                <center>
                  <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editPermission{{ $akses->id }}"><i class="fa fa-pencil fa-lg"></i></button>
                </center>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<!-- Start Modal Permission User -->
<!-- Modal -->
@foreach($permission as $akses)
<div class="modal fade" id="editPermission{{ $akses->id }}" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Akses : {{ $akses->display_name }}</h4>
      </div>
      <div class="modal-body">
        <form action="{{ action('UserController@postEditPermission') }}" method="POST">
          {{ csrf_field() }}
          <input type="hidden" name="id" value="{{ $akses->id }}">

          <div class="form-group">
            <label>Nama Hak Akses</label>
            <input type="text" class="form-control" value="{{ $akses->display_name }}" disabled="">
          </div>

          <div class="form-group">
            <label>Nama Hak Akses</label>
            <textarea class="form-control" name="description">{{ $akses->description }}</textarea>
          </div>

          <div class="clearfix">
            <div class="pull-right">
              <button type="submit" class="btn btn-succes">Simpan</button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
@endforeach()
<!-- End Modal Permission User-->

<!-- Start Modal Edit User -->
<!-- Modal -->
@foreach($user as $editUser)
<div class="modal fade" id="edit{{ $editUser->id }}" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit User {{ $editUser->name }}</h4>
      </div>
      <div class="modal-body">
        <form action="{{ action('UserController@postEditUser') }}" method="POST">
          {{ csrf_field() }}
          <input type="hidden" name="id" value="{{ $editUser->id }}">
          <div class="form-group">
            <label>Nama User</label>
            <input type="text" name="name" value="{{ $editUser->name }}" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" value="{{ $editUser->username }}" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ $editUser->email }}" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Hak Akses</label><br>
              <?php 
                $aksesQuery = "
                  SELECT permissions.display_name, permissions.id
                  FROM permissions, users, user_has_permissions
                  WHERE user_has_permissions.user_id = users.id
                  AND user_has_permissions.permission_id = permissions.id                  
                  AND user_has_permissions.user_id = $editUser->id
                ";

                $hakAkses = [];

                $akses = DB::select(DB::raw($aksesQuery));

                foreach ($akses as $permission) {
                  array_push($hakAkses, $permission->id); 
                  $keys = array_values($hakAkses);
                  $stringKeys = implode(',', $keys);
                  
                  $keysBinder = [];
                  foreach ($keys as $key) {
                    $keysBinder[] = $key;
                  }
                }

                $stringKeysBinder = implode(',', $keysBinder);

                $querySelectedPermission = "
                  SELECT permissions.name, permissions.display_name, permissions.id
                  FROM users, permissions, user_has_permissions
                  WHERE user_has_permissions.user_id = users.id
                  AND user_has_permissions.permission_id = permissions.id
                  AND user_has_permissions.user_id = $editUser->id
                ";

                $queryUnselectedPermission = "
                  SELECT permissions.name, permissions.display_name, permissions.id
                  FROM permissions
                  WHERE permissions.id NOT IN ($stringKeysBinder);
                ";

                $permission = DB::select(DB::raw($querySelectedPermission)); 
                $permission_unselected = DB::select(DB::raw($queryUnselectedPermission)); 
              ?>

              <div class="well">

                @if(count($akses) == 0)
                  <?php 
                    $aksesQuery = "
                      SELECT name, display_name
                      FROM permissions
                    ";
                    $hakakses = DB::select(DB::raw($aksesQuery));

                  ?>
                  @foreach($hakakses as $akses)
                  <label class="checkbox-inline"><input type="checkbox" name="hak_akses[]" value="{{ $akses->name }}">{{ $akses->display_name }}</label>
                  @endforeach
                @else
                  @foreach($permission as $akses)
                  <label class="checkbox-inline"><input type="checkbox" name="hak_akses[]" checked="checked" value="{{ $akses->name }}">{{ $akses->display_name }}</label>
                  @endforeach
                  @foreach($permission_unselected as $access)
                  <label class="checkbox-inline"><input type="checkbox" name="hak_akses[]" value="{{ $access->name }}">{{ $access->display_name }}</label>
                  @endforeach
                @endif
              </div>
          </div>
          <hr>
          <div class="form-group">
            <label>*) Kosongkan Password & Konfirmasi Password jika Password tidak ingin diganti</label>
          </div>
          <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control">
          </div>
          <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" name="password-confirm" class="form-control">
          </div>
          <div class="clearfix">
            <div class="pull-right">
              <button type="submit" class="btn btn-success">Simpan</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endforeach()
<!-- End Modal Edit User-->


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
