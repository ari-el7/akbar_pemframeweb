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
<<<<<<< Updated upstream
            <th>Kategori Nama</th>
=======
            <th>Level Nama</th>
>>>>>>> Stashed changes
        </tr>
        @foreach ($data as $d)
        <tr>
            <td>{{$d->kategori_id}}</td>
            <td>{{$d->kategori_kode}}</td>
<<<<<<< Updated upstream
            <td>{{$d->kategori_nama}}</td>
=======
            <td>{{$d->level_nama}}</td>
>>>>>>> Stashed changes
        </tr>
        @endforeach
    </table>
</body>
</html>