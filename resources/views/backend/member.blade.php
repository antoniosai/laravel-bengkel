@extends('layouts.main')

@section('title')
  Management Member
@endsection

@section('custom_styles')
  <style type="text/css">
    .seperator-table {
      width: 20px;
    }

  </style>
@endsection

@section('content')
<div class="well">
  <h3>Manajemen Member
    <div class="pull-right">
      <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#tambahMember"><i class="fa fa-user-plus fa-lg"></i> Tambah Member</button>
    </div>
  </h3>
</div>
<table class="table table-striped table-hover table-condensed" id="member">
  <thead>
    <tr class="info">
      <th style="width: 30px"><center>No</center></th>
      <th style="width: 180px"><center>Nama Member</center></th>
      <th style="width: 100px"><center>Handphone</center></th>
      <th><center>Alamat</center></th>
      <th style="width: 120px"><center>Poin Terkumpul</center></th>
      <th style="width: 80px"><center>Poin Sisa</center></th>
      <th style="width: 100px"><center>Tipe Member</center></th>
      <th style="width: 20px"><center></center></th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; ?>
    @foreach($member as $listMember)
    <tr>
      <td><center>{{ $no++ }}</center></td>
      <td><center><a href="{{ route('report.member', $listMember->id) }}">{{ $listMember->nama_member }}</a></center></td>
      <td><center>{{ $listMember->handphone }}</center></td>
      <td><center>{{ $listMember->alamat }}</center></td>
      <td><center>{{ $listMember->poin }}</center></td>
      <td><center>{{ $listMember->sisa_poin }}</center></td> 
      <td><center>{{ ucfirst($listMember->type_member) }}</center></td>
      <td>
        <center>
          
          <a href="{{ route('member.delete', $listMember->id) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash fa-lg"></i></a>

        </center>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

@include('partials.form._addmember')


@endsection

@section('custom_scripts')
<script type="text/javascript">
  $(function() {
    $("#member").dataTable();
  });
</script>
@endsection
