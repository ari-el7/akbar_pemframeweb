@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
    </div>
    <div class="card-body">
        @empty($penjualan)
            <div class="alert alert-danger">Data tidak ditemukan.</div>
            <a href="{{ url('penjualan') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        @else
            <form method="POST" action="{{ url('/penjualan/'.$penjualan->penjualan_id) }}" class="form-horizontal">
                @csrf
                @method('PUT')
                
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Kasir</label>
                    <div class="col-10">
                        <select class="form-control" name="user_id" required>
                            @foreach($user as $u)
                                <option value="{{ $u->user_id }}" {{ $u->user_id == $penjualan->user_id ? 'selected' : '' }}>
                                    {{ $u->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Kode Transaksi</label>
                    <div class="col-10">
                        <input type="text" class="form-control" value="{{ $penjualan->penjualan_kode }}" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Nama Pembeli</label>
                    <div class="col-10">
                        <input type="text" class="form-control" name="pembeli" value="{{ $penjualan->pembeli }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Tanggal</label>
                    <div class="col-10">
                        <input type="datetime-local" class="form-control" name="penjualan_tanggal" 
                               value="{{ date('Y-m-d\TH:i', strtotime($penjualan->penjualan_tanggal)) }}" required>
                    </div>
                </div>

                <h5 class="mt-4">Item Barang</h5>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Harga Satuan</th>
                            <th width="150">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penjualan->detail as $item)
                        <tr>
                            <td>
                                <input type="hidden" name="detail_id[]" value="{{ $item->detail_id }}">
                                <select class="form-control" name="barang_id[]" required>
                                    @foreach($barang as $b)
                                        <option value="{{ $b->barang_id }}" {{ $b->barang_id == $item->barang_id ? 'selected' : '' }}>
                                            {{ $b->barang_nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" value="Rp {{ number_format($item->harga, 0, ',', '.') }}" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control" name="jumlah[]" value="{{ $item->jumlah }}" min="1" required>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="form-group row mt-4">
                    <div class="col-10 offset-2">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a class="btn btn-default ml-1" href="{{ url('penjualan') }}">Batal</a>
                    </div>
                </div>
            </form>
        @endempty
    </div>
</div>
@endsection