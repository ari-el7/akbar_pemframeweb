<?php
namespace App\Imports;

use App\Models\StokModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StokImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new StokModel([
            'barang_id'    => $row['barang_id'],
            'user_id'      => $row['user_id'],
            'stok_tanggal' => $row['tanggal'],
            'stok_jumlah'  => $row['jumlah'],
        ]);
    }
}