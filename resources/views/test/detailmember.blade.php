@extends('layouts.main')

@section('title')
Detail Member
@endsection

@section('content')
<table class="table">
  <tr>
    <td>Nama</td>
    <td>:</td>
    <td><b>Antonio Saiful Islam</b></td>
  </tr>
  <tr>
    <td>Handphone</td>
    <td>:</td>
    <td><b>+62 812 1494 007</b></td>
  </tr>
  <tr>
    <td>Tanggal Gabung</td>
    <td>:</td>
    <td><b>12 Nov</b></td>
  </tr>
</table>
<hr>

<ul class="nav nav-tabs" id="myTab">
  <li class="active"><a data-target="#home" data-toggle="tab">Aktivitas Member</a></li>
</ul>

<div class="tab-content">
  <div class="tab-pane active" id="home">
    <br>
    <table class="table table-striped table-hover"  id="detailMember">
      <thead>
        <tr>
          <th>Nama Barang</th>
          <th style="width: 90px">Qty</th>
          <th>Harga</th>
          <th>Tanggal</th>
        </tr>
      </thead>
      <tbody>
          <tr>
            <td>Oli Reposol</td>
            <td>2</td>
            <td>Rp. 50.000</td>
            <td>28 November 2016</td>
          </tr>
          <tr>
            <td>Oli Reposol</td>
            <td>2</td>
            <td>Rp. 50.000</td>
            <td>28 November 2016</td>
          </tr>
          <tr>
            <td>Oli Reposol</td>
            <td>2</td>
            <td>Rp. 50.000</td>
            <td>28 November 2016</td>
          </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection

@section('custom_scripts')
<script type="text/javascript">
  $(function() {
    $("#detailMember").dataTable();
  });
</script>
@endsection
