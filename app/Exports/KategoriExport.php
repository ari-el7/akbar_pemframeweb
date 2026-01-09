<?php

namespace App\Exports;

use App\Models\KategoriModel;
use Maatwebsite\Excel\Concerns\FromCollection;

class KategoriExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return KategoriModel::all();
    }

    public function headings(): array {
    return ['No', 'Kode Kategori', 'Nama Kategori'];
}
public function map($kategori): array {
    return [
        $kategori->kategori_id,
        $kategori->kategori_kode,
        $kategori->kategori_nama,
    ];
}
}
