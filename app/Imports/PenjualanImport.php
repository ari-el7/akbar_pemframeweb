<?php
namespace App\Imports;

use App\Models\PenjualanModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PenjualanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new PenjualanModel([
            'user_id'           => $row['user_id'],
            'pembeli'           => $row['pembeli'],
            'penjualan_kode'    => $row['kode_penjualan'],
            'penjualan_tanggal' => $row['tanggal'],
        ]);
    }
}