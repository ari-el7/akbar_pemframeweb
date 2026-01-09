<?php
namespace App\Exports;

use App\Models\StokModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StokExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return StokModel::with(['barang', 'user'])->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Barang',
            'Nama User (Penerima)',
            'Tanggal Stok',
            'Jumlah Stok',
        ];
    }

    public function map($stok): array
    {
        return [
            $stok->stok_id,
            $stok->barang->barang_nama,
            $stok->user->nama,
            $stok->stok_tanggal,
            $stok->stok_jumlah,
        ];
    }
}