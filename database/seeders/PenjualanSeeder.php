<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Faker::create();
        $data = [];

        $userIds = [1, 2, 3]; 
        
        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'penjualan_id' => $i,
                'user_id' => $faker->randomElement($userIds),
                'pembeli' => $faker->name,
                'penjualan_kode' => 'PJL' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'penjualan_tanggal' => $faker->dateTimeBetween('-1 year', 'now'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('t_penjualan')->insert($data);
    }
}