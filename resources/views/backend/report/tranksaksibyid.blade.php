@extends('layouts.main')

@section('title')
  Detail Nota
@endsection

@section('content')
@include('partials.navbar')
@include('partials.alert')
@include('partials.warning')
<h3>Detail Faktur
  <div class="pull-right">
    <a href="javascript:history.go(-1)" class="btn btn-info btn-sm">Back</a>
    <a href="{{ route('nota.pdf', $ket_tranksaksi->nota_id) }}" class="btn btn-info btn-sm">Print to PDF</a>
  </div>
</h3>
<hr>
<div class="row">
  <div class="col-md-7">
    
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
        <td><strong><strong>{{ App\Member::find($ket_tranksaksi->member_id)->no_member }}</strong></strong></td>
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
    <table class="table table-bordered table-hover table-condensed">
      <thead>
        <tr class="info">
          <th style="width: 5%"><center>No</center></th>
          <th style="width: 35%"><center>Barang</center></th>
          <th><center>Harga</center></th>
          <th style="width: 10%"><center>Jumlah</center></th>
          <th><center>Sub Total</center></th>
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
                Rp {{ number_format($listTranksaksi->harga_umum) }}
              @else
                @if($barang->harga_khusus == 0)
              Rp {{ number_format($listTranksaksi->harga_umum) }}
              @else
                {{-- <s>Rp {{ number_format($barang->harga_jual) }}</s> --}}
                  Rp {{ number_format($listTranksaksi->harga_khusus) }}
                  @if($member->nama_member != 'Guest')
                    <span class='label label-success'>Harga Khusus</span> 
                  @endif
                @endif
              @endif

              
            </center>
          </td>
          <td><center>{{ $listTranksaksi->qty }}</center></td>
          <td><center>Rp {{ number_format($listTranksaksi->total) }}</center></td>
        </tr>
        @endforeach
        <tr class="info">
          <td colspan="4"><center><b>Total</b></center></td>
          <td><center>Rp {{ number_format($total) }}</center></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="col-md-5" style="margin-top: -7px">
    <h3>Keterangan Barang</h3>
    <hr>
    <table class="table table-striped table-bordered table-condensed">
      <thead>
        <tr class="success">
          <th>Nama Barnag</th>
          <th>Harga Pokok</th>
          <th>Harga Umum</th>
          <th>Harga Khusus</th>
        </tr>
      </thead>
      <tbody>
        @foreach($tranksaksi as $barang)
        <tr>
          <td>{{ App\Barang::find($barang->barang_id)->nama_barang }}</td>
          <td>Ro {{ number_format($barang->harga_pokok) }}</td>
          <td>Ro {{ number_format($barang->harga_umum) }}</td>
          <td>Ro {{ number_format($barang->harga_khusus) }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
