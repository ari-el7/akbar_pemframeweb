<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StokSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];

        for ($i = 1; $i <= 15; $i++) {
            $data[] = [
                'stok_id' => $i,
                'supplier_id' => rand(1, 3),
                'barang_id' => $i,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now()->subDays(rand(0, 5)),
                'stok_jumlah' => rand(10, 100),
            ];
        }

        DB::table('t_stok')->insert($data);
    }
}
