<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function index() {
        // DB::insert('insert into m_kategori (kategori_kode, level_nama, created_at) values(?, ?, ?)', 
        //     ['KTG06', 'ATK', now()]
        // );
        // return 'Insert data kategori baru berhasil';

        // $row = DB::update('update m_kategori set level_nama = ? where kategori_kode = ?', 
        //     ['ATK', 'KTG06']
        // );
        // return 'Update data kategori berhasil. Jumlah data yg di update: '.$row.' baris'; 

        // $row = DB::delete('delete from m_kategori where kategori_kode = ?', 
        //     ['KTG06']
        // );
        // return 'Delete data kategori berhasil. Jumlah data yg dihapus: '.$row.' baris'; 

        $data = DB::select('select * from m_kategori');
        return view('kategori', ['data' => $data] );
    }
}
