@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header"><h3 class="card-title">{{ $page->title }}</h3></div>
    <div class="card-body">
        @empty($penjualan)
            <div class="alert alert-danger">Data tidak ditemukan.</div>
        @else
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-sm">
                        <tr><th>Kode Transaksi</th><td>{{ $penjualan->penjualan_kode }}</td></tr>
                        <tr><th>Nama Pembeli</th><td>{{ $penjualan->pembeli }}</td></tr>
                        <tr><th>Tanggal</th><td>{{ $penjualan->penjualan_tanggal }}</td></tr>
                        <tr><th>Kasir</th><td>{{ $penjualan->user->nama }}</td></tr>
                    </table>
                </div>
            </div>
            <h5 class="mt-4">Daftar Barang</h5>
            <table class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($penjualan->detail as $idx => $item)
                        @php $subtotal = $item->harga * $item->jumlah; $total += $subtotal; @endphp
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>{{ $item->barang->barang_nama }}</td>
                            <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Total Bayar</th>
                        <th>Rp {{ number_format($total, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        @endempty
        <a href="{{ url('penjualan') }}" class="btn btn-default mt-3">Kembali</a>
    </div>
</div>
@endsection