<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Barang Masuk</title>
  <!-- <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" media="screen"> -->
  <!-- <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet"> -->
  <style>

  table {
    border-collapse: collapse;
    width: 100%;
  }

  th, td {
    text-align: left;
    padding: 8px;
  }

  tr:nth-child(even){background-color: #f2f2f2}

  th {
    background-color: #6A6B6B;
    color: white;
  }
  </style>
</head>
<body>
  <div style="text-align: center; margin-top: -20px">
    <h2>Area Bengkel</h2>
    <p>Jl. Wanaraja No. 12</p>
    <p>
      No. HP : 0812345678910 | E-Mail : test@mail.com
    </p>
    <hr>
    <h3>Laporan Barang Masuk</h3>
  </div>
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Barang</th>
        <th>Stok  Masuk</th>
        <th>Tanggal Masuk</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; ?>
      @foreach($barangMasuk as $barang)
      <tr>
        <td>{{ $no++ }}</td>
        <td>{{ $barang->nama_barang }}</td>
        <td>{{ $barang->stok_masuk }}</td>
        <td>{{ App\Http\Controllers\LibraryController::waktuIndonesia($barang->created_at) }}</td>

      </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
