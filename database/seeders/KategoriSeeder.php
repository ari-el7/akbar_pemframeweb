<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
<<<<<<< Updated upstream
            ['kategori_id' => 1, 'kategori_kode' => 'KTG01', 'kategori_nama' => 'Elektronik'],
            ['kategori_id' => 2, 'kategori_kode' => 'KTG02', 'kategori_nama' => 'Pakaian'],
            ['kategori_id' => 3, 'kategori_kode' => 'KTG03', 'kategori_nama' => 'Makanan'],
            ['kategori_id' => 4, 'kategori_kode' => 'KTG04', 'kategori_nama' => 'Perabotan'],
            ['kategori_id' => 5, 'kategori_kode' => 'KTG05', 'kategori_nama' => 'Alat Tulis'],
=======
            ['kategori_id' => 1, 'kategori_kode' => 'KTG01', 'level_nama' => 'Elektronik'],
            ['kategori_id' => 2, 'kategori_kode' => 'KTG02', 'level_nama' => 'Pakaian'],
            ['kategori_id' => 3, 'kategori_kode' => 'KTG03', 'level_nama' => 'Makanan'],
            ['kategori_id' => 4, 'kategori_kode' => 'KTG04', 'level_nama' => 'Perabotan'],
            ['kategori_id' => 5, 'kategori_kode' => 'KTG05', 'level_nama' => 'Alat Tulis'],
>>>>>>> Stashed changes
        ];

        DB::table('m_kategori')->insert($data);
    }
}
