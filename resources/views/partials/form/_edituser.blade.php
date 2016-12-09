<!-- Modal -->
<div class="modal fade" id="editUser{{$listUser->id}}" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit User {{ $listUser->name }}</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" role="form" method="POST" action="{{ action('UserController@postAddUser') }}">
            {{ csrf_field() }}
            <div class="form-group">
              <label>Nama User</label>
              <input type="text" name="name" value="{{ $listUser->name}}" class="form-control">
            </div>
            <div class="form-group">
              <label>Username</label>
              <input type="text" name="username" value="{{ $listUser->username}}" class="form-control">
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="text" name="email" value="{{ $listUser->email}}" class="form-control">
            </div>

            <div class="form-group">
              <label>Password</label>
              <input type="password" name="password" class="form-control">
            </div>

            <div class="form-group">
              <label>Password Confirmation</label>
              <input type="password" name="password_confirmation" class="form-control">
            </div>
        </form>
      </div>
    </div>

  </div>
</div>
