@extends('layouts.main')

@section('title')
Point of Sales
@endsection

@section('custom_styles')
<script src="{{ asset('js/vue.min.js')}}"></script>
@endsection

@section('content')
@include('partials.navbar')
  <div class="well">
    <div class="row" style="margin-left: 20px">
      <div class="col-md-2">
        <h5>Tanggal {{ $poin }}</h5>
        <h4><strong>{{ date('d M Y') }}</strong></h4>
      </div>
      <div class="col-md-2">
        <h5>Nomor Nota</h5>
        <h4><strong>{{ $nota }}</strong></h4>
      </div>

      <div class="col-md-3">
        <h5>Operator</h5>
        <h4><strong>{{ Auth::user()->name }}</strong></h4>
      </div>
      <!-- Start Pilih Member -->
      <div class="col-md-5">
        <?php $memberGuest = App\Member::where('nama_member', '=', 'Guest')->first(); ?>
        @if($member_id != $memberGuest->id)
          <?php  $member = App\Member::findOrFail($member_id); ?>
        <div class="row">
          <div class="col-md-6">
            <h5>Member </h5>
            <h4>
              <strong>
                <form class="form-inline" action="{{ action('PosController@unsetMember') }}" method="post">
                  {{ csrf_field() }}
                  <input type="hidden" name="member_id" value="{{ $memberGuest->id }}">
                  <input type="hidden" name="nota" value="{{ $nota }}">
                  <div class="form-group">
                    <label>{{ $member->nama_member }}</label>
                    <button type="submit" class="btn btn-xs btn-warning" class="form-control">Ganti Member</button>
                  </div>
                </form>
              </strong>
            </h4>
          </div>
          <div class="col-md-6">
            <div class="col-md-2">
              <h5>Poin</h5>
              <h4><strong>+{{ $poin }}</strong></h4>
            </div>
          </div>
        </div>
        @else
        <h5>Pilih Member</h5>
        <div class="form-group ">
          <div class="row">
            <form  action="{{ action('PosController@applyMember') }}" method="POST">
              {{ csrf_field() }}
              <input type="hidden" name="nota" value="{{ $nota }}">
              <div class="col-md-8">
                <select class="js-selectize" id="member_id" name="member_id">
                  <option value="" selected="selected"></option>
                  @foreach($member as $anggota)
                  <!-- <li><a href="#">{{ $anggota->nama_member }}</a></li> -->
                  <option value="{{ $anggota->id }}">{{ $anggota->nama_member }} - {{ $anggota->no_member }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <button type="submit" class="btn btn-success btn-sm">Pilih</button>
              </div>
            </form>
          </div>
        </div>
        @endif
      </div>
      <!-- End Pilih Member -->

    </div>
  </div>
  <hr>
  @include('partials.alert')
  @include('partials.warning')
  <div class="col-md-6">
    <div class="well">
      <h4>Pilih Barang untuk Ditambahkan</h4>
      <hr>
      <form class="" action="{{ action('PosController@saveOrder')}}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="member_id" value="{{$member_id}}">
        <input type="hidden" name="nota_id" value="{{ $nota }}">
        <div class="row">
          <div class="col-md-7">
            <div class="form-group">
              <select class="js-selectize" id="barang_id" name="barang_id">
                <option disabled value="">Pilih Barang</option>
                <option value="" selected="selected"></option>
                @foreach($barang as $barangs)
                <option value="{{ $barangs->id }}">{{ $barangs->nama_barang}} | Rp {{ number_format($barangs->harga_jual) }} </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <input type="number" class="form-control input-sm" value="{{ old('qty') }}" name="qty" value="" placeholder="Qty...">
            </div>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-success btn-sm">Tambah</button>
            <!-- <a href="#" class="btn btn-success btn-sm">Tambah</a> -->
          </div>
        </div>
      </div>
    </form>
  </div>

  <div class="col-md-6">
    <div class="well">
      @if(count($order) == 0)
      <marquee><h3>Keranjang Belanja Masih Kosong</h3></marquee>
      @else
        <table class="table table-hover table-striped">
          <thead>
            <tr>
              <th>Nama Barang</th>
              <th style="width: 20px">Qty</th>
              <th>Harga</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($order as $item)
              <tr>
                <td>{{ $item->nama_barang }} @ Rp {{ number_format($item->harga_jual) }}</td>
                <td>
                  <div class="form-group">
                    <form class="" action="{{ action('PosController@updateQty') }}" method="post">
                      {{ csrf_field() }}
                      <input type="hidden" name="member_id" value="{{ $member_id }}">
                      <input type="hidden" name="barang_id" value="{{ $item->barang_id }}">
                      <input type="hidden" name="id" value="{{ $item->id }}">
                      <input type="hidden" name="nota" value="{{ $nota }}">
                      <input v-model="qty" type="number" name="qty" value="{{ $item->qty }}" style="width: 40px">

                  </div>
                </td>
                <td style="width: 120px">
                  <div class="app">
                    <!-- Rp. @{{ number_format($item->total) }} -->
                    Rp {{ number_format($item->total) }}
                  </div>
                </td>
                <td style="width: 130px">
                    <button type="submit" class="btn btn-xs btn-info">Update</button>
                  </form>
                  <a href="{{ route('order.delete', [$item->nota_id, $item->barang_id] ) }}" class="btn btn-xs btn-warning">Delete</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>

    <div class="row">
      <div class="col-md-4">
        <div class="alert alert-success">
          Sub Total
          <h4>Rp {{ number_format($total) }}</h4>
        </div>
      </div>
      <div class="col-md-4">
        <div class="alert alert-warning">
          Discount
          <h4>{{ $diskon }}%</h4>
        </div>
      </div>
      <div class="col-md-4">
        <div class="alert alert-danger">
          Total
          <h4>Rp {{ number_format($grand_total) }}</h4>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-6">
            <form class="form-inline" action="{{ action('PosController@saveTranksaksi') }}" method="post">
              {{ csrf_field() }}
              <input type="hidden" name="member_id" value="{{$member_id}}">
              <input type="hidden" name="nota_id" value="{{ $nota }}">
              <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
              <?php $grandTotal = 0 ?>
              @foreach($order as $listOrder)
              <?php $no = 1; ?>
                <input type="hidden" name="qty[]" value="{{ $listOrder->qty }}">
                <input type="hidden" name="barang_id[]" value="{{ $listOrder->barang_id }}">
                <input type="hidden" name="total[]" value="{{ $listOrder->total}}">
                <?php $grandTotal = $grandTotal + $listOrder->total; ?>
              @endforeach
              <input type="hidden" name="diskon" value="{{ $diskon }}">
              <input type="hidden" name="grand_total" value="{{ $grandTotal }}">
              <input type="hidden" name="poin" value="{{ $poin }}">
              <div class="form-group">
                <label>Jumlah Bayar</label>
                <div class="input-group">
                  <span class="input-group-addon">Rp</span>
                  <input type="text" name="bayar" placeholder="Masukan Jumlah Bayar" class="form-control">
                </div>
              </div>
              <input name="rows[]" value="1" type="hidden">
          </div>
          <div class="col-md-4">
            <label>Kembalian</label>
            <h4>Rp {{ number_format(50000) }}</h4>
          </div>
          <div class="col-md-2">
            <label style="color: white">Proses</label>
            <div class="pull-right">
              <button type="submit" class="btn btn-success">Proses</button>
            </div>
          </form>
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection

@section('custom_scripts')
<script type="text/javascript">
$(document).ready(function() {
  $('#data').DataTable();
  });
</script>

<script type="text/javascript">
  $(document).ready(function() {
    $('.js-selectize').selectize({
      sortField: 'text'
    });
  });
</script>

<script type="text/javascript">
  var app = new Vue({
    el: '.app',
    data: {
      qty: '',
      total: ''
    }
  })
</script>
@endsection
