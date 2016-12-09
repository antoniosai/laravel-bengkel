@extends('layouts.main')

@section('title')
Menu Utama
@endsection

@section('content')
@include('partials.navbar')
    <div class="well">
      <h4>Point Of Sales (POS)
        <div class="pull-right">
          <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#tukarPoin">Tukar Poin</button>
          <a href="{{ url('admin/pos/') }}" class="btn btn-success btn-xs">Buat POS Baru</a>
        </div>
      </h4>
    </div>
    @include('partials.alert')
    <ul class="nav nav-tabs" id="myTab">
      <li class="active"><a data-target="#pendingSales" data-toggle="tab">Pending Sales</a></li>
    </ul>

    <div class="tab-content">
      <div class="tab-pane active" id="pendingSales">
        <br>
        <table class="table table-hover table-striped" id="pos">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>No Nota</th>
              <th>Member</th>
              <th>Total Belanja</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($order as $list)
            <tr>
              <td>{{ App\Http\Controllers\LibraryController::waktuIndonesia($list->created_at) }}</td>
              <td>{{ $list->nota_id }}</td>
              <td>{{ $list->nama_member }}</td>
              <td>Rp. {{ number_format($list->total )}}</td>
              <td>
                <a href="{{ url('admin/pos/'.$list->nota_id.'/'.$list->id) }}" class="btn btn-warning btn-xs">Buka</a>
                <a href="{{ route('nota.delete', $list->nota_id) }}" class="btn btn-danger btn-xs">Hapus</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
@include('partials.form._addbarang')
@include('partials.form._addmember')
@include('partials.form._tukarpoin')
@endsection

@section('custom_scripts')
<script type="text/javascript">
  $(document).ready(function() {
    $('.js-selectize').selectize({
      sortField: 'text'
    });

    $("#pos").dataTable();
  });
</script>
@endsection
