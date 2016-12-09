<!-- Modal -->
<div class="modal fade" id="tambahMember" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Member</h4>
      </div>
      <div class="modal-body">
        <form action="{{ action('MemberController@postAddMember')}}" method="post">
          {{ csrf_field() }}
          <div class="form-group">
            <label>Nama Member</label>
            <input type="text" name="nama_member" placeholder="Masukkan Nama Member" class="form-control">
          </div>
          <div class="form-group">
            <label>Handphone</label>
            <input type="text" name="handphone" placeholder="Masukkan Nomor Handphone" class="form-control">
          </div>
          <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" rows="3" cols="40" class="form-control" placeholder="Alamat Member"></textarea>
          </div>
          <div class="form-group clearfix">
            <button type="submit" class="btn btn-info pull-right">Tambah Member</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
