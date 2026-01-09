<!DOCTYPE html>
<html lang="en">
<head>
    <title>Data Kategori</title>
</head>
<body>
    <h1>Data Kategori</h1>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>ID Kategori</th>
            <th>Kategori Kode</th>
            <th>Kategori Nama</th>
        </tr>
        @foreach ($data as $d)
        <tr>
            <td>{{$d->kategori_id}}</td>
            <td>{{$d->kategori_kode}}</td>
            <td>{{$d->kategori_nama}}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>