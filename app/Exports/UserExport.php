<?php

namespace App\Exports;

use App\Models\UserModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return UserModel::with('level')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Username',
            'Nama Pengguna',
            'Level Pengguna',
        ];
    }

    public function map($user): array
    {
        return [
            $user->user_id,
            $user->username,
            $user->nama,
            $user->level->level_nama, // Mengambil nama level dari relasi
        ];
    }
}