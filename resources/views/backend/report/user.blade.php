@extends('layouts.main')

@section('title')
Laporan Member
@endsection

@section('custom_styles')

@endsection

@section('content')
@include('partials.navbar')

<div class="well">
  <h3>Laporan User <small><a href="#" title="Laporan Laba Rugi" data-toggle="popover" data-trigger="focus" data-content="Halaman untuk menampilkan laporan laba rugi (bulanan)"><i class="fa fa-question-circle fa-lg"></i></a></small></h3>
</div>
@include('partials.alert')
<div class="row">
  <div class="col-md-12">
    <br>
    <table class="table table-hover table-striped table-bordered table-condensed" id="user">
      <thead>
        <tr class="info">
          <th style="width: 5%"><center>No</center></th>
          <th style="width: 20%"><center>Nama Pegawai</center></th>
          <th style="width: 20%"><center>Username</center></th>
          <th style="width: 20%"><center>Email</center></th>
          <th><center>Tanggal Daftar</center></th>
          <th><center>Aksi</center></th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1 ?>
        @foreach($user as $pegawai)
        <tr>
          <td><center>{{ $no++ }}</center></td>
          <td><center>{{ $pegawai->name }}</center></td>
          <td><center>{{ $pegawai->username }}</center></td>
          <td><center>{{ $pegawai->email }}</center></td>
          <td><center>{{ App\Http\Controllers\LibraryController::waktuIndonesia($pegawai->created_at) }}</center></td>
          <td><center><a href="{{ route('report.user.byid', $pegawai->id) }}" class="btn btn-success btn-xs">Detail</a></center></td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <!-- <canvas id="poinMember" width="400" height="300"></canvas> -->
  </div>
</div>
@endsection

@section('custom_scripts')
<script src="{{ asset('js/Chart.min.js') }}"></script>

<script type="text/javascript">
$(document).ready(function() {
  $('#user').DataTable();
  });
</script>


@endsection
