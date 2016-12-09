<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Dummy Text</title>
  </head>
  <body>
    User ini telah memesan
    <table border="1">
      <thead>
        <tr>
          <td>Tanngal</td>
          <td>Nama</td>
          <td>Sebanyak</td>
          <td>Harga</td>
          <td>Oleh Member</td>
        </tr>
      </thead>
      <tbody>
        @foreach($data as $hasil)
        <tr>
          <td>{{ $hasil->created_at }}</td>
          <td>{{ $hasil->nama_barang }}</td>
          <td>{{ $hasil->stok }}</td>
          <td>{{ $hasil->total }}</td>
          <td>{{ $hasil->nama_member }}</td>
        </tr>
        @endforeach
        <tr>
          <td>

          </td>
          <td colspan="2">testa</td>
        </tr>
      </tbody>
    </table>
  </body>
</html>
