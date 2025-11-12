<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'supplier_id' => 1,
                'supplier_kode' => 'SUP01',
                'supplier_nama' => 'PT Maju Jaya',
                'supplier_alamat' => 'Jl. Melati No. 10',
            ],
            [
                'supplier_id' => 2,
                'supplier_kode' => 'SUP02',
                'supplier_nama' => 'CV Sumber Rejeki',
                'supplier_alamat' => 'Jl. Mawar No. 5',
            ],
            [
                'supplier_id' => 3,
                'supplier_kode' => 'SUP03',
                'supplier_nama' => 'UD Sejahtera',
                'supplier_alamat' => 'Jl. Kenanga No. 8',
            ],
        ];

        DB::table('m_supplier')->insert($data);
    }
}
