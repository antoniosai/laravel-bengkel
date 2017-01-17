@extends('layouts.main')

@section('title')
  Detail Nota
@endsection

@section('content')
<h3>Detail Penjualan <small>(No Nota : {{ $ket_tranksaksi->nota_id }})</small>
  <div class="pull-right">
    <a href="javascript:history.go(-1)" class="btn btn-info btn-sm">Back</a>
    <a href="{{ route('nota.pdf', $ket_tranksaksi->nota_id) }}" class="btn btn-info btn-sm"><i class="fa fa-lg fa-file-pdf-o"></i> &nbsp;Print to PDF</a>
  </div>
</h3>
<hr>
<div class="row">
  <div class="col-md-12">
    
    <table style="width: 50%; float: left">
      <tr>
        <td>Tanggal</td>
        <td style="width:30px"></td>
        <td>:</td>
        <td style="width:30px"></td>
        <td><strong><strong>{{ App\Http\Controllers\LibraryController::waktuIndonesia($ket_tranksaksi->created_at) }}</strong></strong></td>
      </tr>
      <tr>
        <td>No Faktur Meber</td>
        <td style="width:30px"></td>
        <td>:</td>
        <td style="width:30px"></td>
        <td><strong>{{ $ket_tranksaksi->faktur_id }}</strong></td>
      </tr>
    </table>
    <table style="width: 50%; float: left">
      <tr>
        <td>Member ID</td>
        <td style="width:30px"></td>
        <td>:</td>
        <td style="width:30px"></td>
        <td><strong><strong>{{ App\Member::find($ket_tranksaksi->member_id)->nama_member }}</strong></strong></td>
      </tr>
      <tr>
        <td>Kasir</td>
        <td style="width:30px"></td>
        <td>:</td>
        <td style="width:30px"></td>
        <td><strong>{{ App\User::find($ket_tranksaksi->user_id)->name }}</strong></td>
      </tr>
    </table>
    <br>
    <br>
    <hr>
    <table class="table table-striped table-hover table-condensed">
      <thead>
        <tr class="info">
          <th style="width: 5%"><center>No</center></th>
          <th style="width: 35%"><center>Barang</center></th>
          <th><center>Harga</center></th>
          <th style="width: 10%"><center>Jumlah</center></th>
          <th><center>Sub Total</center></th>
          <th style="width:60px"><center></center></th>
        </tr>
      </thead>
      <tbody>
        <?php
          $no = 1;
          $total = 0;
          $subtotal = 0;

          $barangList = [];

          array_push($barangList, $tranksaksi);
        ?>
        @foreach($tranksaksi as $listTranksaksi)
        <?php
          $total = $total + $listTranksaksi->total;
          $subtotal = $subtotal + $listTranksaksi->total;

          $barang = App\Barang::findOrFail($listTranksaksi->barang_id);
          $member = App\Member::findOrFail($listTranksaksi->member_id);
        ?>
        <tr>
          <td><center>{{ $no++ }}</center></td>
          <td>
            <center>
              {{ $listTranksaksi->nama_barang }}
              
            </center>
          </td>
          <td>
            <center>

              @if($member->nama_member == "Guest")
                <span class='label label-default'>Harga Umum</span>
                Rp {{ number_format($listTranksaksi->harga_umum) }}
              @else
                @if($member->type_member == "grosir")
                  Rp {{ number_format($listTranksaksi->harga_grosir) }}
                  <span class='label label-warning'>Harga Grosir</span>
                @else
                  @if($barang->harga_khusus == 0)
                    <span class='label label-default'>Harga Umum</span>
                    Rp {{ number_format($listTranksaksi->harga_umum) }}
                  @else
                  {{-- <s>Rp {{ number_format($barang->harga_jual) }}</s> --}}
                    Rp {{ number_format($listTranksaksi->harga_khusus) }}
                    @if($member->nama_member != 'Guest')
                      <span class='label label-success'>Harga Khusus</span> 
                    @endif
                  @endif
                @endif
              @endif
              
            </center>
          </td>
          <td><center>{{ $listTranksaksi->qty }}</center></td>
          <td><center>Rp {{ number_format($listTranksaksi->total) }}</center></td>
          <td>
            <center>
              <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#returnBarang{{ $listTranksaksi->tranksaksi_id }}">Return</button>
            </center>
          </td>
        </tr>
        @endforeach
        <tr class="info">
          <td colspan="4"><center><b>Total</b></center></td>
          <td><center>Rp {{ number_format($total) }}</center></td>
          <td><center></center></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="col-md-12" style="margin-top: -7px">
    <h3>Keterangan Barang</h3>
    <hr>
    <table class="table table-striped table-bordered table-condensed">
      <thead>
        <tr class="success">
          <th><center>Nama Barang</center></th>
          <th style="width: 180px"><center>Harga Pokok</center></th>
          <th style="width: 180px"><center>Harga Umum</center></th>
          <th style="width: 180px"><center>Harga Khusus</center></th>
          <th style="width: 180px"><center>Harga Grosir</center></th>
        </tr>
      </thead>
      <tbody>
        @foreach($tranksaksi as $barang)
        <tr>
          <td><center>{{ App\Barang::find($barang->barang_id)->nama_barang }}</center></td>
          <td><center>Rp {{ number_format($barang->harga_pokok) }}</center></td>
          <td><center>Rp {{ number_format($barang->harga_umum) }}</center></td>
          <td>
            <center>
              @if($barang->harga_khusus == "")
                -
              @else
                Rp {{ number_format($barang->harga_khusus) }}
              @endif
            </center>
          </td>
          <td>
            Rp {{ number_format($barang->harga_grosir) }}
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

@foreach($tranksaksi as $listTranksaksi)
<div class="modal fade" id="returnBarang{{ $listTranksaksi->tranksaksi_id }}" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Return Barang <i>{{ App\Barang::find($listTranksaksi->barang_id)->nama_barang }}</i></h4>
      </div>
      <div class="modal-body">
        <form action="{{ action('ReturnController@returns')}}" method="post">
          {{ csrf_field() }}
          <input type="hidden" name="tranksaksi_id" value="{{ $listTranksaksi->tranksaksi_id }}">
          <input type="hidden" name="barang_id" value="{{ $listTranksaksi->barang_id }}">
          <input type="hidden" name="member_id" value="{{ $listTranksaksi->member_id }}">
          <input type="hidden" name="user_id" value="{{ $listTranksaksi->user_id }}">
          <input type="hidden" name="nota_id" value="{{ $listTranksaksi->nota_id }}">

          <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" disabled class="form-control" value="{{ App\Barang::find($listTranksaksi->barang_id)->nama_barang }}">
          </div>
          <div class="form-group">
            <label>Qty</label>
            <input type="text" class="form-control" name="qty" placeholder="Maksimal {{ $listTranksaksi->qty }} items">
          </div>
          <div class="form-group">
            <label>Alasan Return</label>
            <textarea class="form-control" name="alasan"></textarea>
          </div>
          <hr>
          <div class="form-group clearfix">
            <button type="submit" class="btn btn-info pull-right">Return Barang</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endforeach

@endsection
