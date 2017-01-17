<div class="modal fade" id="prosesTranksaksi" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Hapus Data Tranksaksi</h4>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ action('TokoController@pemutihanData') }}">
          {{ csrf_field() }}
          <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control">
          </div>
          <div class="form-group">
            <label>Password Confirmation</label>
            <input type="password" name="password_confirmation" class="form-control">
          </div>
          <div class="form-group">
            <label>Pilih Report Yang Akan Dihapus</label>
              <input type="checkbox" name="report[]" value="tranksaksi"> Tranksaksi
              <input type="checkbox" name="report[]" value="tranksaksi"> Tranksaksi
              <input type="checkbox" name="report[]" value="tranksaksi"> Tranksaksi
              <input type="checkbox" name="report[]" value="tranksaksi"> Tranksaksi
              <input type="checkbox" name="report[]" value="tranksaksi"> Tranksaksi
              <br>
            </div>
          </div>
          <div class="clearfix">
            <div class="">
              <button type="submit" class="btn btn-success">Proses</button>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
