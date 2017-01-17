@extends('layouts.main')

@section('title')
  Halaman Tidak Ditemukan
@endsection

@section('content')
  @include('partials.navbar')
  <div class="jumbotron">
    <center>
      <h1>Error404</h1>
      <h2>Halaman Tidak Ditemukan</h2>
      <a href="javascript:history.go(-1)" class="btn btn-info btn-sm">Back</a>
    </center>
  </div>
@endsection
