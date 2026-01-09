<?php

namespace App\Imports;

use App\Models\KategoriModel;
use Maatwebsite\Excel\Concerns\ToModel;

class KategoriImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model(array $row) {
    return new KategoriModel([
        'kategori_kode' => $row['kode_kategori'], 
        'kategori_nama' => $row['nama_kategori'],
    ]);
}
}   
