@extends('layouts.main')

@section('title')
Report
@endsection

@section('custom_styles')

@endsection

@section('content')
<div class="well">
  <h3>Menu Report</h3>
</div>
<div class="row">
  <div class="col-md-6">
    <h3>Report Poin Member
      <div class="pull-right">
        <a href="#" class="btn btn-sm btn-success">Cetak PDF</a>
      </div>
    </h3>
    <br>
    <canvas id="poinMember" width="400" height="300"></canvas>
  </div>
  <div class="col-md-6">
    <h3>Barang Masuk
      <div class="pull-right">
        <a href="{{ action('ExportController@barangMasukToPdf') }}" class="btn btn-sm btn-success">Cetak PDF</a>
      </div>
    </h3>
    <br>
    <canvas id="barangMasuk" width="400" height="300"></canvas>
  </div>
</div>
@endsection

@section('custom_scripts')
<script src="{{ asset('js/Chart.min.js') }}"></script>

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

<script>
  var data = {
      labels: {!! json_encode($barang) !!},
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
              data: {!! json_encode($barangMasuk) !!}
          }
      ]
  };

  var ctx = document.getElementById("barangMasuk").getContext("2d");
  var myLineChart = new Chart(ctx).Bar(data);

</script>
@endsection
