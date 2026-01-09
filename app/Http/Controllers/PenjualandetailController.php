<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualandetailController extends Controller
{
    public function index() {

        // DB::insert('insert into t_penjualan_detail (penjualan_id, barang_id, harga, jumlah, created_at) values(?, ?, ?, ?, ?)', 
        //     [10, 15, 150000, 1, now()]
        // );
        // return 'Insert data detail penjualan baru berhasil';

        // $row = DB::update('update t_penjualan_detail set jumlah = ? where detail_id = ?', 
        //     [3, 36]
        // );
        // return 'Update data detail penjualan berhasil. Jumlah data yg di update: '.$row.' baris';

        // $row = DB::delete('delete from t_penjualan_detail where detail_id = ?', 
        //     [36]
        // );
        // return 'Delete data detail penjualan berhasil. Jumlah data yg dihapus: '.$row.' baris';

        $data = DB::select('select * from t_penjualan_detail');
        return view('penjualandetail', ['data' => $data] );
    }
}
