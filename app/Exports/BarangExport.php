<?php

namespace App\Exports;

use App\Models\BarangModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BarangExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return BarangModel::with('kategori')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Barang',
            'Nama Barang',
            'Harga Beli',
            'Harga Jual',
            'Kategori',
        ];
    }

    public function map($barang): array
    {
        return [
            $barang->barang_id,
            $barang->barang_kode,
            $barang->barang_nama,
            $barang->harga_beli,
            $barang->harga_jual,
            $barang->kategori->kategori_nama, // Mengambil nama kategori dari relasi
        ];
    }
}