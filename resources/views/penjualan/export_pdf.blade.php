<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body{
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 15px;
        }
        table { width: 100%; border-collapse: collapse; }
        td, th { font-size: 11px; }
        th { text-align: center; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .mb-1 { margin-bottom: 1px; }
        .d-block { display: block; }
    </style>
</head>
<body>
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center"><img src="{{ public_path('adminlte/dist/img/AdminLTELogo.png') }}" height="70"></td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI MALANG</span>
                <span class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang 65141</span>
                <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101-105, 0341-404420, Fax. (0341) 404420</span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>

    <h3 class="text-center">LAPORAN DATA PENJUALAN</h3>
    
    <table class="table-data" border="1" cellpadding="3">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Pembeli</th>
                <th>Kasir</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan as $p)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $p->penjualan_kode }}</td>
                <td>{{ $p->pembeli }}</td>
                <td>{{ $p->user->nama }}</td>
                <td>{{ $p->penjualan_tanggal }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>