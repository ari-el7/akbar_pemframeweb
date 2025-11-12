<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index() {

        // DB::insert('insert into m_barang (kategori_id, barang_kode, barang_nama, harga_beli, harga_jual, created_at) values(?, ?, ?, ?, ?, ?)', 
        //     [1, 'BRG16', 'Laptop Gaming', 10000000, 12000000, now()]
        // );
        // return 'Insert data barang baru berhasil';

        // $row = DB::update('update m_barang set harga_jual = ? where barang_kode = ?', 
        //     [13000000, 'BRG16']
        // );
        // return 'Update data barang berhasil. Jumlah data yg di update: '.$row.' baris';

        // $row = DB::delete('delete from m_barang where barang_kode = ?', 
        //     ['BRG16']
        // );
        // return 'Delete data barang berhasil. Jumlah data yg dihapus: '.$row.' baris';

        $data = DB::select('select * from m_barang');
        return view('barang', ['data' => $data] );
    }
}
