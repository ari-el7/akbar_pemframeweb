<?php

namespace App\Exports;

use App\Models\SupplierModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SupplierExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return SupplierModel::orderBy('supplier_kode')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Supplier',
            'Nama Supplier',
            'Alamat Supplier',
        ];
    }

    public function map($supplier): array
    {
        return [
            $supplier->supplier_id,
            $supplier->supplier_kode,
            $supplier->supplier_nama,
            $supplier->supplier_alamat,
        ];
    }
}