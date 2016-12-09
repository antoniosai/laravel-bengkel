<!-- Modal -->
<div class="modal fade" id="tukarPoin" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Poin</h4>
      </div>
      <div class="modal-body">
        <form  action="{{ action('PosController@tukarPoin') }}" method="POST">
          <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
          {{ csrf_field() }}
          <div class="form-group">
            <label for="member_id">Pilih Member</label>
            <select class="js-selectize" id="member_id" name="member_id">
              <option value="" selected="selected"></option>
              @foreach($member as $anggota)
              <!-- <li><a href="#">{{ $anggota->nama_member }}</a></li> -->
              <option value="{{ $anggota->id }}">{{ $anggota->nama_member }} ---- Sisa Poin : {{ $anggota->poin }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="barang_id">Pilih Barang</label>
            <select class="js-selectize" id="barang_id" name="barang_id">
              <option value="" selected="selected"></option>
              @foreach($barang as $listBarang)
              <!-- <li><a href="#">{{ $anggota->nama_member }}</a></li> -->
              <option value="{{ $listBarang->id }}">{{ $listBarang->nama_barang }} | Poin : {{ $listBarang->bobot_poin }} | Stok : {{ $listBarang->stok }} | Harga : {{ $listBarang->harga }}</option>
              @endforeach
            </select>
          </div>
          <button type="submit" class="btn btn-success btn-sm">Pilih</button>
        </form>
      </div>
    </div>

  </div>
</div>
