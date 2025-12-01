<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    public function index() {

        // DB::insert('insert into t_stok (supplier_id, barang_id, user_id, stok_tanggal, stok_jumlah, created_at) values(?, ?, ?, ?, ?, ?)', 
        //     [1, 1, 1, now(), 50, now()]
        // );
        // return 'Insert data stok baru berhasil';

        // $row = DB::update('update t_stok set stok_jumlah = ? where stok_id = ?', 
        //     [55, 16]
        // );
        // return 'Update data stok berhasil. Jumlah data yg di update: '.$row.' baris';

        // $row = DB::delete('delete from t_stok where stok_id = ?', 
        //     [16]
        // );
        // return 'Delete data stok berhasil. Jumlah data yg dihapus: '.$row.' baris';

        $data = DB::select('select * from t_stok');
        return view('stok', ['data' => $data] );
    }
}
