<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index() {
    
        // DB::insert('insert into t_penjualan (user_id, pembeli, penjualan_kode, penjualan_tanggal, created_at) values(?, ?, ?, ?, ?)', 
        //     [3, 'Budi Santoso', 'PJL011', now(), now()]
        // );
        // return 'Insert data penjualan baru berhasil';

        // $row = DB::update('update t_penjualan set pembeli = ? where penjualan_kode = ?', 
        //     ['Budi Santoso (Member)', 'PJL011']
        // );
        // return 'Update data penjualan berhasil. Jumlah data yg di update: '.$row.' baris';

        // $row = DB::delete('delete from t_penjualan where penjualan_kode = ?', 
        //     ['PJL011']
        // );
        // return 'Delete data penjualan berhasil. Jumlah data yg dihapus: '.$row.' baris';

        $data = DB::select('select * from t_penjualan');
        return view('penjualan', ['data' => $data] );
    }
}
