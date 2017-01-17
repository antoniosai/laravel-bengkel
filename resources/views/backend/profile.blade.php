@extends('layouts.main')

@section('title')
Profil {{ $user->name}}
@endsection

@section('custom_styles')

@endsection

@section('content')

<div class="well" style="padding-top: 0px">
  <div class="row">
    <div class="col-md-3">
      <h3><small>Profil</small><br>{{ $user->name }} <small>(ID : {{ $user->id }})</small></h3>
    </div>
    <div class="col-md-3">
      <h3>
        <small>Tanggal Daftar</small><br>
        {{ App\Http\Controllers\LibraryController::waktuIndonesia($user->created_at) }}
      </h3>
    </div>
    <?php 
      $aksesQuery = "
        SELECT permissions.display_name, permissions.id
        FROM permissions, users, user_has_permissions
        WHERE user_has_permissions.user_id = users.id
        AND user_has_permissions.permission_id = permissions.id                  
        AND user_has_permissions.user_id = $user->id
      ";

      $hakAkses = [];

      $akses = DB::select(DB::raw($aksesQuery));

      $totalAkses = count($akses);
    ?>
    <div class="col-md-6">
      <h3>
        <small>Hak Akses<br>
        @forelse($akses as $permission)
          <?php 

            if ($totalAkses != 0) {
                array_push($hakAkses, $permission->id); 
                $keys = array_values($hakAkses);
                $stringKeys = implode(',', $keys);
                
                $keysBinder = [];
                foreach ($keys as $key) {
                  $keysBinder[] = $key;
                }
                $stringKeysBinder = implode(',', $keysBinder);
            } else {
                $stringKeysBinder = null;
            }
            
          ?>
          <span class="label label-primary label-lg">{{ $permission->display_name }}</span>
        @empty
          <span class="label label-danger label-lg">Tidak memiliki hak akses</span>
        @endforelse
        </small>
      </h3>
    </div>

  </div>
</div>

<h3>Edit Profile</h3>
<hr>
<form class="form-horizontal" role="form" method="POST" action="{{ action('UserController@postEditUser') }}">
        <input type="hidden" class="form-control" name="id" value="{{ $user->id }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="col-md-2 control-label">Name</label>

            <div class="col-md-6">
                <input  type="text" class="form-control" name="name" value="{{ $user->name }}" required autofocus>

                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        @if($user->username == 'admin')
        <input type="hidden" name="username" value="admin">
        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
            <label for="username" class="col-md-2 control-label">Username</label>

            <div class="col-md-6">
                <input disabled type="text" class="form-control"  value="{{ $user->username }}" required autofocus>

                @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        @else
        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
            <label for="username" class="col-md-2 control-label">Username</label>

            <div class="col-md-6">
                <input type="text" class="form-control" name="username" value="{{ $user->username }}" required autofocus>

                @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        @endif

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="col-md-2 control-label">E-Mail Address</label>

            <div class="col-md-6">
                <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <hr>
      
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <div class="col-md-2"></div>
            <label for="password" class="col-md-6">*) Kosongkan Password & Konfirmasi Password jika Password tidak ingin diganti</label>
        </div>
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="col-md-2 control-label">Password</label>

            <div class="col-md-6">
                <input type="password" class="form-control" name="password">

                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="password-confirm" class="col-md-2 control-label">Konfirmasi Password</label>

            <div class="col-md-6">
                <input type="password" class="form-control" name="password_confirmation">
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-2 col-md-offset-2">
                <button type="submit" class="btn btn-primary">
                    Simpan Profile
                </button>
            </div>
        </div>
    </form>

@endsection

@section('custom_scripts')
<script type="text/javascript">
$(document).ready(function() {
  $('#tukarPoin').DataTable();
  });
</script>

<script type="text/javascript">
$(document).ready(function() {
  $('#tranksaks').DataTable();
  });
</script>
@endsection
