@extends('layouts.main')

@section('title')
Laporan Member
@endsection

@section('custom_styles')

@endsection

@section('content')
<div class="well">
  <h3>Pilih Member Untuk Melihat Akivitas</h3>
</div>
<div class="row">
  <div class="col-md-12">

    <br>
    <table class="table table-hover table-condensed" id="member">
      <thead>
        <tr class="info">
          <th><center>No</center></th>
          <th><center>Nama</center></th>
          <th><center>Poin</center></th>
          <th><center>Handphone</center></th>
          <th><center>Tanggal Daftar</center></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1 ?>
        @foreach($member_eloquent as $listMember)
        <tr>
          <td><center>{{ $no++ }}</center></td>
          <td><center>{{ $listMember->nama_member }}</center></td>
          <td><center>{{ $listMember->poin }}</center></td>
          <td><center>{{ $listMember->handphone }}</center></td>
          <td><center>{{ App\Http\Controllers\LibraryController::waktuIndonesia($listMember->created_at) }}</td>
          <td>
            <a href="{{ route('report.member', $listMember->id) }}" class="btn btn-xs btn-info">Detail</a>
          </center></td>
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
  $('#member').DataTable();
  });
</script>

<script>
  var data = {
      labels: {!! json_encode($member) !!},
      datasets: [
          {
              fillColor: "rgba(151,187,205,0.5)",
              strokeColor: "rgba(151,187,205,0.8)",
              pointColor: "rgba(220,220,220,1)",
              pointStrokeColor: "#fff",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(220,220,220,1)",
              highlightFill: "rgba(151,187,205,0.75)",
              highlightStroke: "rgba(151,187,205,1)",
              data: {!! json_encode($poin) !!}
          }
      ]
  };

  var ctx = document.getElementById("poinMember").getContext("2d");
  var myLineChart = new Chart(ctx).Bar(data);

</script>

@endsection
