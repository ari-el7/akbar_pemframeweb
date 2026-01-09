<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PenjualanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Faker::create();
        $detailData = [];
        $penjualanIds = DB::table('t_penjualan')->pluck('penjualan_id')->toArray();
        $barangIds = DB::table('m_barang')->pluck('barang_id')->toArray(); 
        
        $detailId = 1;

        foreach ($penjualanIds as $penjualanId) {
            
            $selectedBarangIds = $faker->randomElements($barangIds, 3, true);

            foreach ($selectedBarangIds as $barangId) {
                $barang = DB::table('m_barang')->where('barang_id', $barangId)->first();
                
                if ($barang) {
                    $hargaJual = $barang->harga_jual;
                    $qty = $faker->numberBetween(1, 5);
                    $subtotal = $qty * $hargaJual;

                    $detailData[] = [
                        'detail_id' => $detailId++,
                        'penjualan_id' => $penjualanId,
                        'barang_id' => $barangId,
                        'harga' => $hargaJual,
                        'jumlah' => $qty,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        DB::table('t_penjualan_detail')->insert($detailData);
    }
}