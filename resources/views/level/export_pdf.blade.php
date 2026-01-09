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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            font-size: 11px;
        }
        th {
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .font-11 {
            font-size: 11px;
        }
        .font-13 {
            font-size: 13px;
        }
        .font-10 {
            font-size: 10px;
        }
        .font-bold {
            font-weight: bold;
        }
        .mb-1 {
            margin-bottom: 1px;
        }
        .d-block {
            display: block;
        }
        .table-data td, .table-data th {
            padding: 4px;
        }
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

    <h3 class="text-center">LAPORAN DATA LEVEL USER</h3>
    
    <table class="table-data" border="1">
        <thead>
            <tr>
                <th width="10%">No</th>
                <th width="30%">Kode Level</th>
                <th width="60%">Nama Level</th>
            </tr>
        </thead>
        <tbody>
            @foreach($level as $l)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $l->level_kode }}</td>
                <td>{{ $l->level_nama }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>