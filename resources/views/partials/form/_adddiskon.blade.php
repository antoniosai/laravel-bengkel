<!-- Modal -->
<div class="modal fade" id="tambahDiskon" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Diskon</h4>
      </div>
      <div class="modal-body">
        <form action="{{ action('BonusController@postAddDiskon')}}" method="post">
          {{ csrf_field() }}
          <div class="form-group">
            <label>Harga Belanja</label>
            <div class="input-group">
              <span class="input-group-addon">Rp</span>
              <input type="text" name="harga_belanja" placeholder="Masukan Harga Belanja" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label>Diskon</label>
            <input type="number" name="diskon" placeholder="Masukan Poin" class="form-control">
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-info">Tambah Diskon</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
