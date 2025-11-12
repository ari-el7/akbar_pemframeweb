<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
     public function index() {
        // DB::insert('insert into m_supplier (supplier_kode, supplier_nama, supplier_alamat, created_at) values(?, ?, ?, ?)', 
        //     ['SUP04', 'PT. Maju Berkah', 'Jl. Merdeka No. 10', now()]
        // );
        // return 'Insert data supplier baru berhasil';

        // $row = DB::update('update m_supplier set supplier_alamat = ? where supplier_kode = ?', 
        //     ['Jl. Sudirman No. 5', 'SUP04']
        // );
        // return 'Update data supplier berhasil. Jumlah data yg di update: '.$row.' baris';

        // $row = DB::delete('delete from m_supplier where supplier_kode = ?', 
        //     ['SUP04']
        // );
        // return 'Delete data supplier berhasil. Jumlah data yg dihapus: '.$row.' baris';

        $data = DB::select('select * from m_supplier');
        return view('supplier', ['data' => $data] );
    }
}
