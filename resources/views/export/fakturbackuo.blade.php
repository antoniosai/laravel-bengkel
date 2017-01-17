@extends('export.layout')

@section('header')
Faktur
@endsection

@section('title')
Faktur
@endsection

@section('content')
<div style="padding-top: -12px; padding-bottom:20px">
  <table style="width: 50%; float: left">
    <tr>
      <td>Tanggal</td>
      <td style="width:30px"></td>
      <td>:</td>
      <td style="width:30px"></td>
      <td><strong><strong>{{ App\Http\Controllers\LibraryController::waktuIndonesia($faktur->created_at) }}</strong></strong></td>
    </tr>
    <tr>
      <td>No Faktur Meber</td>
      <td style="width:30px"></td>
      <td>:</td>
      <td style="width:30px"></td>
      <td><strong>{{ $faktur->faktur_id }}</strong></td>
    </tr>
  </table>
  <table style="width: 50%; float: left; ">
    <tr>
      <td>Member ID</td>
      <td style="width:30px"></td>
      <td>:</td>
      <td style="width:30px"></td>
      <td><strong><strong>{{ App\Member::find($faktur->member_id)->nama_member }}</strong></strong></td>
    </tr>
    <tr>
      <td>Kasir</td>
      <td style="width:30px"></td>
      <td>:</td>
      <td style="width:30px"></td>
      <td><strong>{{ App\User::find($faktur->user_id)->name }}</strong></td>
    </tr>
  </table>
</div>
<br>
<br>
<br><br><br>
<hr>
<table class="table table-bordered table-hover table-condensed">
  <thead>
    <tr class="header">
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
              @endif
            @endif
          @endif

          
        </center>
      </td>
      <td><center>{{ $listTranksaksi->qty }}</center></td>
      <td><center>Rp {{ number_format($listTranksaksi->total) }}</center></td>
    </tr>
    @endforeach
    <tr class="header">
      <td colspan="4"><center><b>Total</b></center></td>
      <td><center>Rp {{ number_format($total) }}</center></td>
    </tr>
  </tbody>
</table>
@endsection