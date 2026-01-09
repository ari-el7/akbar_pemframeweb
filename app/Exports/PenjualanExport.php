<?php
namespace App\Exports;

use App\Models\PenjualanModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PenjualanExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return PenjualanModel::with('user')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Penjualan',
            'Nama Pembeli',
            'Kasir (User)',
            'Tanggal Penjualan',
        ];
    }

    public function map($penjualan): array
    {
        return [
            $penjualan->penjualan_id,
            $penjualan->penjualan_kode,
            $penjualan->pembeli,
            $penjualan->user->nama,
            $penjualan->penjualan_tanggal,
        ];
    }
}