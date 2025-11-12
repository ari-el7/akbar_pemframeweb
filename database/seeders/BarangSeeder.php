<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['barang_id' => 1, 'kategori_id' => 1, 'barang_kode' => 'BRG01', 'barang_nama' => 'Laptop', 'harga_beli' => 5000000, 'harga_jual' => 6500000],
            ['barang_id' => 2, 'kategori_id' => 1, 'barang_kode' => 'BRG02', 'barang_nama' => 'Smartphone', 'harga_beli' => 3000000, 'harga_jual' => 4000000],
            ['barang_id' => 3, 'kategori_id' => 2, 'barang_kode' => 'BRG03', 'barang_nama' => 'Kemeja', 'harga_beli' => 80000, 'harga_jual' => 120000],
            ['barang_id' => 4, 'kategori_id' => 2, 'barang_kode' => 'BRG04', 'barang_nama' => 'Celana', 'harga_beli' => 100000, 'harga_jual' => 150000],
            ['barang_id' => 5, 'kategori_id' => 3, 'barang_kode' => 'BRG05', 'barang_nama' => 'Roti', 'harga_beli' => 10000, 'harga_jual' => 15000],
            ['barang_id' => 6, 'kategori_id' => 3, 'barang_kode' => 'BRG06', 'barang_nama' => 'Susu', 'harga_beli' => 20000, 'harga_jual' => 30000],
            ['barang_id' => 7, 'kategori_id' => 4, 'barang_kode' => 'BRG07', 'barang_nama' => 'Kursi', 'harga_beli' => 150000, 'harga_jual' => 200000],
            ['barang_id' => 8, 'kategori_id' => 4, 'barang_kode' => 'BRG08', 'barang_nama' => 'Meja', 'harga_beli' => 200000, 'harga_jual' => 275000],
            ['barang_id' => 9, 'kategori_id' => 5, 'barang_kode' => 'BRG09', 'barang_nama' => 'Pulpen', 'harga_beli' => 3000, 'harga_jual' => 5000],
            ['barang_id' => 10, 'kategori_id' => 5, 'barang_kode' => 'BRG10', 'barang_nama' => 'Buku Tulis', 'harga_beli' => 5000, 'harga_jual' => 8000],
            ['barang_id' => 11, 'kategori_id' => 1, 'barang_kode' => 'BRG11', 'barang_nama' => 'Headset', 'harga_beli' => 100000, 'harga_jual' => 150000],
            ['barang_id' => 12, 'kategori_id' => 2, 'barang_kode' => 'BRG12', 'barang_nama' => 'Kaos', 'harga_beli' => 60000, 'harga_jual' => 90000],
            ['barang_id' => 13, 'kategori_id' => 3, 'barang_kode' => 'BRG13', 'barang_nama' => 'Snack', 'harga_beli' => 5000, 'harga_jual' => 8000],
            ['barang_id' => 14, 'kategori_id' => 4, 'barang_kode' => 'BRG14', 'barang_nama' => 'Lemari', 'harga_beli' => 400000, 'harga_jual' => 500000],
            ['barang_id' => 15, 'kategori_id' => 5, 'barang_kode' => 'BRG15', 'barang_nama' => 'Pensil', 'harga_beli' => 2000, 'harga_jual' => 4000],
        ];

        DB::table('m_barang')->insert($data);
    }
}
