@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/penjualan/create_ajax') }}')" class="btn btn-sm btn-primary mt-1">Tambah Transaksi (Ajax)</button>
             <button onclick="modalAction('{{ url('/penjualan/import') }}')" class="btn btn-sm btn-info mt-1">Import</button>
            <a href="{{ url('/penjualan/export_excel') }}" class="btn btn-sm btn-primary mt-1">Export Excel</a>
            <a href="{{ url('/penjualan/export_pdf') }}" class="btn btn-sm btn-warning mt-1">Export PDF</a>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-striped table-hover table-sm" id="table_penjualan">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Penjualan</th>
                    <th>Pembeli</th>
                    <th>Kasir (User)</th>
                    <th>Tanggal Transaksi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>

@endsection

@push('js')
<script>
    var tablePenjualan;

    // Fungsi modalAction untuk memanggil form ajax
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    $(document).ready(function() {
        tablePenjualan = $('#table_penjualan').DataTable({
            serverSide: true, // serverSide: true, jika ingin menggunakan server side processing
            ajax: {
                "url": "{{ url('penjualan/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function (d) {
                    // Jika ada filter bisa ditambahkan di sini
                }
            },
            columns: [
                {
                    data: "DT_RowIndex", 
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "penjualan_kode", 
                    orderable: true,
                    searchable: true
                },
                {
                    data: "pembeli", 
                    orderable: true,
                    searchable: true
                },
                {
                    data: "user.nama", // Mengambil nama kasir dari relasi user
                    orderable: true,
                    searchable: true
                },
                {
                    data: "penjualan_tanggal", 
                    orderable: true,
                    searchable: false
                },
                {
                    data: "aksi", 
                    orderable: false,
                    searchable: false,
                    className: "text-center"
                }
            ]
        });
    });
</script>
@endpush