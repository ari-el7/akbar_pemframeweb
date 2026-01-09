<?php

namespace App\Imports;

use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new UserModel([
            'level_id' => $row['level_id'], // pastikan di excel nama headernya level_id
            'username' => $row['username'],
            'nama'     => $row['nama'],
            'password' => Hash::make($row['password']), // Password di-hash
        ]);
    }
}