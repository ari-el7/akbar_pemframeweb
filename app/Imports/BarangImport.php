<?php

namespace App\Imports;

use App\Models\BarangModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BarangImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new BarangModel([
            'barang_kode' => $row['kode'],      // sesuaikan dengan nama kolom di excel
            'barang_nama' => $row['nama'],
            'harga_beli'  => $row['harga_beli'],
            'harga_jual'  => $row['harga_jual'],
            'kategori_id' => $row['kategori_id'], // pastikan excel nulis ID kategori (angka)
        ]);
    }
}