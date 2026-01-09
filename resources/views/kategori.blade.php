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
<<<<<<< HEAD
            <th>Kategori Nama</th>
=======
            <th>Level Nama</th>
>>>>>>> 8ff7ed2912a5ba3f923844970145ce6766b169aa
        </tr>
        @foreach ($data as $d)
        <tr>
            <td>{{$d->kategori_id}}</td>
            <td>{{$d->kategori_kode}}</td>
<<<<<<< HEAD
            <td>{{$d->kategori_nama}}</td>
=======
            <td>{{$d->level_nama}}</td>
>>>>>>> 8ff7ed2912a5ba3f923844970145ce6766b169aa
        </tr>
        @endforeach
    </table>
</body>
</html>