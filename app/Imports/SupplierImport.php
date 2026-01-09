<?php

namespace App\Imports;

use App\Models\SupplierModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SupplierImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new SupplierModel([
            'supplier_kode'   => $row['kode_supplier'], // sesuaikan dengan header di excel
            'supplier_nama'   => $row['nama_supplier'],
            'supplier_alamat' => $row['alamat_supplier'],
        ]);
    }
}