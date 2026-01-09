<?php
namespace App\Imports;
use App\Models\LevelModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LevelImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new LevelModel([
            'level_kode' => $row['kode_level'], // sesuaikan nama header di excel nanti
            'level_nama' => $row['nama_level'],
        ]);
    }
}