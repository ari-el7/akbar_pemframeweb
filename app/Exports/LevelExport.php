<?php
namespace App\Exports;
use App\Models\LevelModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LevelExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return LevelModel::all();
    }
    public function headings(): array
    {
        return ['No', 'Kode Level', 'Nama Level'];
    }
    public function map($level): array
    {
        return [
            $level->level_id,
            $level->level_kode,
            $level->level_nama,
        ];
    }
}