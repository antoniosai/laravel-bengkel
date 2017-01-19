@extends('layouts.main')

@section('title')
Point of Sales
@endsection

@section('custom_styles')
<script src="{!! asset('js/vue.min.js')!!}"></script>
@endsection

@section('content')
<div class="well" style="height: 90px; padding-top: 6px">
  <div class="row" style="margin-left: 20px">
    <div class="col-md-2">
      <h5>Tanggal</h5>
      <h4><strong>{!! date('d M Y') !!}</strong></h4>
    </div>
    <div class="col-md-2">
      <h5>Nomor Nota</h5>
      <h4><strong>{!! $nota !!}</strong></h4>
    </div>

    <div class="col-md-2">
      <h5>Operator</h5>
      <h4><strong>{!! Auth::user()->name !!}</strong></h4>
    </div>
    <!-- Start Pilih Member -->
    <div class="col-md-6">
      <?php $memberGuest = App\Member::where('nama_member', '=', 'Guest')->first(); ?>
      @if($member_id != $memberGuest->id)
      <?php  $member = App\Member::findOrFail($member_id); ?>
      <div class="row">
        <div class="col-md-6">
          <h5>Member</h5>
          <h4>
            <strong>
              {!! $member->nama_member !!}</label>
              
            </strong>
          </h4>
        </div>
        <div class="col-md-5">
          <h4><span class="label label-success">Tipe Member :
            <?php $typeMember = ''; ?> 
            @if($member->type_member == 'grosir')
              <?php $typeMember = 'GR'; ?>
              {!! $typeMember !!}
            @else
              <?php $typeMember = 'RG'; ?>
              {!! $typeMember !!}
            @endif
          </span></h4>
          <form class="form-inline" action="{!! action('PosController@unsetMember') !!}" method="post">
                {!! csrf_field() !!}
                <input type="hidden" name="member_id" value="{!! $memberGuest->id !!}">
                <input type="hidden" name="nota" value="{!! $nota !!}">
                <div class="form-group">
                  <label>
                  <button type="submit" class="btn btn-xs btn-warning" class="form-control">Ganti Member</button>
                </div>
              </form>
        </div>
      </div>
      @else
      <h5>Pilih Member</h5>
      <div class="form-group ">
        <div class="row">
          <form  action="{!! action('PosController@applyMember') !!}" method="POST">
            {!! csrf_field() !!}
            <input type="hidden" name="nota" value="{!! $nota !!}">
            <div class="col-md-10">
              <select class="js-selectize" id="member_id" name="member_id">
                <option value="" selected="selected"></option>
                @foreach($member as $anggota)
                <?php $typeMember = ''; ?> 
                @if($anggota->type_member == 'grosir')
                  <?php $typeMember = 'GR'; ?>
                  {!! $typeMember !!}
                @else
                  <?php $typeMember = 'RG'; ?>
                  {!! $typeMember !!}
                @endif
                <!-- <li><a href="#">{!! $anggota->nama_member !!}</a></li> -->
                <option value="{!! $anggota->id !!}">{!! $typeMember !!} - {!! $anggota->nama_member !!} - {!! $anggota->no_member !!}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-2">
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
<?php $memberAktif = App\Member::findOrFail($member_id); ?>

<div class="row">
  <div class="col-md-12">
    <div class="well" style="height: 48px; padding-top: 6px">
      <form class="" action="{!! action('PosController@saveOrder')!!}" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="member_id" value="{!! $member_id !!}">
        <input type="hidden" name="nota_id" value="{!! $nota !!}">
        <div class="row">
          <div class="col-md-9">
            <div class="form-group">
              <select class="js-selectize form-control input-sm" id="barang_id" name="barang_id">
                <option disabled value="">Pilih Barang</option>
                <option value="" selected="selected"></option>
                <?php

                  $spasiBarang = 0;
                  $n = count($barang);
                  $maxBarang = 0;
                  $maxHarga = 0;
                  $maxStok = 0;
                  $panjangBarang = 0;

                  $spasi = 10;

                  $dataBarang = [];
                  
                  foreach ($barang as $barangs) {
                    array_push($dataBarang, strlen($barangs->nama_barang)); 
                    $maxBarang = max($dataBarang);
                    $panjangBarang = strlen($barangs->nama_barang);
                  }

                  $maxSpasiBarang = $maxBarang + $spasi;

                  $spasiBarang = "&ensp;";

                ?>
                @foreach($barang as $barangs)
                <?php 
                  $selisihPanjangBarang = $maxSpasiBarang - strlen($barangs->nama_barang);
                ?>
                <option value="{!! $barangs->id !!}">
                  {!! strtoupper($barangs->nama_barang) !!}
                  {!! str_repeat($spasiBarang, $selisihPanjangBarang) !!}| 
                  @if($memberAktif->nama_member == 'Guest')
                    Rp {!! number_format($barangs->harga_jual) !!} | 
                  @else
                    @if($memberAktif->type_member == 'grosir')
                      Rp {!! number_format($barangs->harga_grosir) !!} | 
                    @else
                      @if($barangs->harga_khusus == "")
                        Rp {!! number_format($barangs->harga_jual) !!} | 
                      @else
                        Rp {!! number_format($barangs->harga_khusus) !!} | 
                      @endif
                    @endif
                  @endif
                  Stok : {!! $barangs->stok !!}
                </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <input type="number" class="form-control input-sm" value="{!! old('qty') !!}" name="qty" value="" placeholder="Qty...">
            </div>
          </div>
          <div class="col-md-1">
            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-lg fa-plus"></i></button>
            <!-- <a href="#" class="btn btn-success btn-sm">Tambah</a> -->
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="col-md-7">
    @if(count($order) == 0)
  <div class="well">
    <marquee><h3>Keranjang Belanja Masih Kosong</h3></marquee>
  </div>
  @else
  <table class="table table-hover table-striped table-condensed">
    <thead>
      <tr class="info">
        <th><center>No</center></th>
        <th><center>Nama Barang</center></th>
        <th style="width: 20px"><center>Qty</center></th>
        <th><center>Harga</center></th>
        <th style="width:15%"></th>
      </tr>
    </thead>
    <tbody>
      <?php $total = 0; $no = 1;?>
      @foreach($order as $item)
      <tr>
        <td><center>{!! $no++ !!}</center></td>
        <td><center>{!! $item->nama_barang !!} @ Rp {!! number_format($item->total / $item->qty) !!}</center></td>
        <td>
          <div class="form-group">
            <form class="" action="{!! action('PosController@updateQty') !!}" method="post">
              {!! csrf_field() !!}
              <input type="hidden" name="member_id" value="{!! $member_id !!}">
              <input type="hidden" name="barang_id" value="{!! $item->barang_id !!}">
              <input type="hidden" name="id" value="{!! $item->id !!}">
              <input type="hidden" name="nota" value="{!! $nota !!}">
              <input v-model="qty" type="number" name="qty"  value="{!! $item->qty !!}" style="width: 50px">

            </div>
          </td>
          <td style="width: 120px">
            <div class="app">
              <?php $total = $total + $item->total; ?>
              <!-- Rp. @{!! number_format($item->total) !!} -->
              <center>Rp {!! number_format($item->total) !!}</center>
            </div>
          </td>
          <td style="width: 80px">
            <button type="submit" class="btn btn-xs btn-info"><i class="fa fa-check fa-lg"></i></button>
          </form>
          <a href="{!! route('order.delete', [$item->nota_id, $item->barang_id] ) !!}" class="btn btn-xs btn-warning"><i class="fa fa-trash fa-lg"></i></a>
        </td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr class="info">
        <td colspan="3"><center><strong>Total</strong></center></td>
        <td><center><strong>Rp {!! number_format($total) !!}</strong></center></td>
        <td></td>
      </tr>
    </tfoot>
  </table>
  @endif
  </div>
  <div class="col-md-5">
    <form class="form-inline" action="{!! action('PosController@saveTranksaksi') !!}" method="post">
      <div class="row">
        <div class="col-md-3">
          <a href="{!! url('admin/pos/') !!}" class="btn btn-sq-lg btn-primary" style="width: 99px">
            <i class="fa fa-file-o fa-3x" aria-hidden="true"></i><br/>
            Nota Baru
          </a>
        </div>

        <div class="col-md-3">
          <a href="{{ action('ReturnController@index') }}" class="btn btn-sq-lg btn-success" style="width: 99px">
            <i class="fa fa-arrow-circle-o-left fa-3x"></i><br/>
            Return <br>
          </a>
        </div>

        <div class="col-md-3">
          <button type="submit" name="submit" value="simpan" class="btn btn-sq-lg btn-info" style="width: 99px">
            <i class="fa fa-save fa-3x"></i><br/>
            Simpan
          </button>
        </div>

        <div class="col-md-3">
          <button type="submit" name="submit" value="print" class="btn btn-sq-lg btn-danger" style="width: 99px">
            <i class="fa fa-print fa-3x"></i><br/>
            Cetak
          </button>
        </div>
      </div>
      {!! csrf_field() !!}
      <input type="hidden" name="member_id" value="{!!$member_id!!}">
      <input type="hidden" name="nota_id" value="{!! $nota !!}">
      <input type="hidden" name="user_id" value="{!! Auth::user()->id !!}">
      <?php $grandTotal = 0 ?>
      @foreach($order as $listOrder)
      <?php $no = 1; ?>
      <input type="hidden" name="qty[]" value="{!! $listOrder->qty !!}">
      <input type="hidden" name="barang_id[]" value="{!! $listOrder->barang_id !!}">
      <input type="hidden" name="total[]" value="{!! $listOrder->total!!}">
      <?php $grandTotal = $grandTotal + $listOrder->total; ?>
      @endforeach
      <input type="hidden" name="diskon" value="{!! $diskon !!}">
      <input type="hidden" name="grand_total" value="{!! $grandTotal !!}">
      <input type="hidden" name="poin" value="{!! $poin !!}">
      <input name="rows[]" value="1" type="hidden">
    </form>
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
  var kembalian = new Vue({
    el: '#kembalian',
    data: {
      total: {!! $grand_total !!}
      bayar: 0
      kembali: bayar - total
    }
  })
</script>
@endsection
