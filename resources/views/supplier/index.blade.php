@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
             <button onclick="modalAction('{{ url('/supplier/import') }}')" class="btn btn-sm btn-info mt-1">Import</button>
            <a href="{{ url('/supplier/export_excel') }}" class="btn btn-sm btn-primary mt-1">Export Excel</a>
            <a href="{{ url('/supplier/export_pdf') }}" class="btn btn-sm btn-warning mt-1">Export PDF</a>
            <button onclick="modalAction('{{ url('/supplier/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped table-hover table-sm" id="table_supplier">
            <thead>
                <tr><th>ID</th><th>Kode</th><th>Nama Supplier</th><th>Alamat</th><th>Aksi</th></tr>
            </thead>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
<script>
    var tableSupplier; // Definisi variabel global

    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    $(document).ready(function() {
        tableSupplier = $('#table_supplier').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('supplier/list') }}",
                "dataType": "json",
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                {data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false},
                {data: "supplier_kode", className: "", orderable: true, searchable: true},
                {data: "supplier_nama", className: "", orderable: true, searchable: true},
                {data: "supplier_alamat", className: "", orderable: true, searchable: true},
                {data: "aksi", className: "text-center", orderable: false, searchable: false}
            ]
        });
    });
</script>
@endpush