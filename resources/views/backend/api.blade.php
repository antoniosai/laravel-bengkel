@extends('layouts.main')

@section('title')
Custom Api
@endsection

@section('custom_styles')
<link rel="stylesheet" href="{{ asset('css/bootstrap-button.css') }}" media="screen" title="no title">
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@include('partials.navbar')
@include('partials.alert')
<div class="jumbotron">
  <center>
    <h2>{API} <i>Collection</i></h2>
    <hr>
    <button class="btn btn-success btn-lg" type="button" data-toggle="modal" data-target="#apiBarang">
      <i class="fa fa-truck fa-3x" aria-hidden="true"></i><br>
      API Barang
    </button>
    <a href="{{ action('UserController@apiUser') }}" class="btn btn-info btn-lg">
      <i class="fa fa-user fa-3x" aria-hidden="true"></i><br>
      API User
    </a>
    <button class="btn btn-primary btn-lg">
      <i class="fa fa-pencil fa-3x" aria-hidden="true"></i><br>
      API Order
    </button>
    <button class="btn btn-warning btn-lg">
      <i class="fa fa-users fa-3x" aria-hidden="true"></i><br>
      API Member
    </button>
    <button class="btn btn-danger btn-lg">
      <i class="fa fa-truck fa-3x" aria-hidden="true"></i><br>
      API Barang
    </button>
  </center>
</div>

<!-- Modal Barang -->
<div class="modal fade" id="apiBarang" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pilih API</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4">
            <a href="{{ action('BarangController@apiAllBarang') }}" class="btn btn-success btn-xl">
              <i class="fa fa-truck fa-3x" aria-hidden="true"></i><br>
              Semua Barang
            </a>
          </div>
          <div class="col-md-8">
            <form class="form-inline" action="" method="get">
              <div class="form-group">
                <label>Masukan Nama Barang</label><br>
                <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" class="form-control">
                <button type="submit" class="btn btn-info">Kirim</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
