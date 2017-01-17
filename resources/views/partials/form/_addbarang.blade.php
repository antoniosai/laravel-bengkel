<div class="modal fade" id="tambahBarang" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Barang Baru</h4>
      </div>
      <div class="modal-body">
        <form action="{{ action('BarangController@postAddBarang')}}" method="post">
          {{ csrf_field() }}
          <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" placeholder="Masukkan Nama Barang" class="form-control">
          </div>
          <div class="form-group">
            <label>Stok Awal</label>
            <input type="number" name="stok" value="{{ old('stok') }}" placeholder="Stok Awal" class="form-control">
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Harga</label>
                <input type="number" name="harga" value="{{ old('harga') }}" placeholder="Masukkan Harga" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Harga Umum</label>
                <input type="number" name="harga_jual" value="{{ old('harga_jual') }}" placeholder="Masukkan Harga Jual" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Harga Khusus</label>
                <input type="number" name="harga_khusus" value="{{ old('harga_khusus') }}" placeholder="Masukkan Harga Khusus" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Harga Grosir</label>
                <input type="number" name="harga_grosir" value="{{ old('harga_grosir') }}" placeholder="Masukkan Harga Grosir" class="form-control">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Opsi Tukar Poin</label>
            <br>
            <label class="radio-inline">
              <input type="radio" name="opsi_tukarpoin" value="yes"> <span class="label label-success">Ya</span>
            </label>
            <label class="radio-inline">
              <input checked="checked" type="radio" name="opsi_tukarpoin" value="no"> <span class="label label-danger">Tidak</span>
            </label>
          </div>
          <hr>
          <div class="form-group clearfix">
            <button type="submit" class="btn btn-info pull-right">Simpan Barang</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
