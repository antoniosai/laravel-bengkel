@extends('layouts.main')

@section('title')
Point of Sales
@endsection

@section('custom_styles')
<link rel="stylesheet" href="{{ asset('css/bootstrap-button.css') }}" media="screen" title="no title">
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

<style type="text/css">
select {
  width: 200px;
  float: left;
}
.controls {
  width: 40px;
  float: left;
  margin: 10px;
}
.controls a {
  padding: 2px;
  font-size: 14px;
  text-decoration: none;
  display: inline-block;
  text-align: center;
  margin: 5px;
}
</style>
@endsection

@section('content')
@include('partials.navbar')
<div class="well" style="height: 90px; padding-top: 2px">
  <div class="row" style="margin-left: 20px">
    <div class="col-md-2">
      <h5>Tanggal {{ $poin }}</h5>
      <h4><strong>{{ date('d M Y') }}</strong></h4>
    </div>
    <div class="col-md-2">
      <h5>Nomor Nota</h5>
      <h4><strong>{{ $nota }}</strong></h4>
    </div>

    <div class="col-md-3">
      <h5>Operator</h5>
      <h4><strong>{{ Auth::user()->name }}</strong></h4>
    </div>
    <!-- Start Pilih Member -->
    <div class="col-md-5">
      <?php $memberGuest = App\Member::where('nama_member', '=', 'Guest')->first(); ?>
      @if($member_id != $memberGuest->id)
      <?php  $member = App\Member::findOrFail($member_id); ?>
      <div class="row">
        <div class="col-md-6">
          <h5>Member </h5>
          <h4>
            <strong>
              <form class="form-inline" action="{{ action('PosController@unsetMember') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="member_id" value="{{ $memberGuest->id }}">
                <input type="hidden" name="nota" value="{{ $nota }}">
                <div class="form-group">
                  <label>{{ $member->nama_member }}</label>
                  <button type="submit" class="btn btn-xs btn-warning" class="form-control">Ganti Member</button>
                </div>
              </form>
            </strong>
          </h4>
        </div>
        <div class="col-md-6">
          <div class="col-md-2">
            <h5>Poin</h5>
            <h4><strong>+{{ $poin }}</strong></h4>
          </div>
        </div>
      </div>
      @else
      <h5>Pilih Member</h5>
      <div class="form-group ">
        <form  action="{{ action('PosController@applyMember') }}" method="POST">
          <div class="row">
            {{ csrf_field() }}
            <input type="hidden" name="nota" value="{{ $nota }}">
            <div class="col-md-8">
              <select class="js-selectize" id="member_id" name="member_id">
                <option value="" selected="selected"></option>
                @foreach($member as $anggota)
                <!-- <li><a href="#">{{ $anggota->nama_member }}</a></li> -->
                <option value="{{ $anggota->id }}">{{ $anggota->nama_member }} - {{ $anggota->no_member }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
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
@include('partials.alert')
@include('partials.warning')
<div class="col-md-8">
  <form name="selection">
    {{ csrf_field() }}
    <div class="form-group">
      <input type="text" name="search_barang" value="{{ old('search_barang')}}" onkeyup="showHint(this.value)" placeholder="Silahkan cari barang disini..." class="form-control input-sm">
    </div>
    <select multiple size="13" id="from" style="width:300px" class="form-control">
      <!-- <option disabled>Nama Barang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;test</option> -->
      <option value="" id="listBarang"></option>
      <!-- @foreach($barang as $listBarang)
      <option value="{{ $listBarang->id }}" id="listBarang">{{ $listBarang->nama_barang }}</option>
      @endforeach -->
    </select>
    <div class="controls">
      <a href="javascript:moveAll('from', 'to')" class="btn btn-xs btn-primary">&gt;&gt;</a>
      <a href="javascript:moveSelected('from', 'to')" class="btn btn-xs btn-primary"> &gt;</a>
      <a href="javascript:moveSelected('to', 'from')" class="btn btn-xs btn-primary">&lt;</a>
      <a href="javascript:moveAll('to', 'from')" href="#" class="btn btn-xs btn-primary">&lt;&lt;</a>
    </div>
    <select multiple id="to" size="13" name="orders[]" class="form-control" style="width:370px"></select>
  <form>
  <div class="col-md-12">
    <hr>
    <div class="row">
      <div class="col-md-4">
        <div class="alert alert-info">
          Sub Total
          <h4>Rp {{ number_format($total) }}</h4>
        </div>
      </div>
      <div class="col-md-4">
        <div class="alert alert-warning">
          Discount
          <h4>{{ $diskon }}%</h4>
        </div>
      </div>
      <div class="col-md-4">
        <div class="alert alert-danger">
          Grand Total
          <h4>Rp {{ number_format($total) }}</h4>
        </div>
      </div>
    </div>
    <hr>
  </div>
</div>
<div class="col-md-4">
  <center>
    <a href="#" class="btn btn-sq-lg btn-primary">
      <i class="fa fa-print fa-5x" aria-hidden="true"></i><br/>
      Nota Baru
    </a>
    <a href="#" class="btn btn-sq-lg btn-success">
      <i class="fa fa-user fa-5x"></i><br/>
      Return <br>
    </a>
    <div class="" style="margin-top:10px"></div>
    <a href="#" class="btn btn-sq-lg btn-warning">
      <i class="fa fa-save fa-5x"></i><br/>
      Simpan
    </a>
    <a href="#" class="btn btn-sq-lg btn-danger">
      <i class="fa fa-print fa-5x"></i><br/>
      Cetak
    </a>
  </center>
  <br>
  <div class="well">
    <div class="row">
      <div class="col-md-8">
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-addon">Rp</span>
            <input type="number" name="name" value="" class="form-control input-sm" placeholder="Bayar">
          </div>
        </div>
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-addon">Rp</span>
            <input type="number" disabled name="name" value="50000" class="form-control input-sm" placeholder="Kembalian">
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <a href="#" class="btn btn-success btn-sq">
          <i class="fa fa-user fa-3x"></i><br>
          Proses
        </a>
      </div>
    </div>
  </div>
</div>

@endsection

@section('custom_scripts')
<script>
  function moveAll(from, to) {
    $('#'+from+' option').remove().appendTo('#'+to);
  }

  function moveSelected(from, to) {
    $('#'+from+' option:selected').remove().appendTo('#'+to);
  }

  function selectAll() {
    $("select option").attr("selected","selected");
  }
</script>


<script type="text/javascript">
  $(document).ready(function() {

    $('#data').DataTable();

    $('.js-selectize').selectize({
      sortField: 'text'
    });

  });
</script>

<script>
  function showHint(str) {
    if (str.length == 0) {
      document.getElementById("listBarang").innerHTML = "";
      return;
    } else {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("listBarang").innerHTML = this.responseText;
        }
      }

      var listBarang =
      xmlhttp.open("GET", "http://localhost:8000/admin/api/barang/search/"+str, true);
      xmlhttp.send();
    }
  }
</script>

<!-- <script type="text/javascript">
$(document).ready(function() {
  $('select').change(function() {
    var $this = $(this);
    $this.siblings('select').append($this.find('option:selected')); // append selected option to sibling
    $('select', $this.parent()).each(function(i,v){ // loop through relative selects
      var $options = $(v).find('option'); // get all options
      $options = $options.sort(function(a,b){ // sort by value of options
        return a.value - b.value;
      });
      $(this).html($options); // add new sorted options to select
    });
  });
});
</script> -->
@endsection
