<!-- Modal -->
<div class="modal fade" id="tambahBarang" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Hadiah</h4>
      </div>
      <div class="modal-body">
        <form action="{{ action('BonusController@postAddHadiah')}}" method="post">
          {{ csrf_field() }}
          <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" placeholder="Masukan Nama Barang" class="form-control">
          </div>
          <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" placeholder="Masukan Stok Tersedia" class="form-control">
          </div>
          <div class="form-group">
            <label>Bobot Poin</label>
            <input type="number" name="bobot_poin" placeholder="Masukan Bobot Poin" class="form-control">
          </div>
          <div class="form-group">
            <div class="clearfix">
              <div class="pull-right">
                <button type="submit" class="btn btn-info">Tambah Hadiah</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
