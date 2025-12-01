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

        // $data = DB::select('select * from m_kategori');
        // return view('kategori', ['data' => $data] );

        /* $data = [
            'kategori_kode' => 'SNK',
            'kategori_nama' => 'Snack/Makanan Ringan',
            'created_at' => now()
        ];

        DB::table('m_kategori')->insert($data);
        return 'Insert data baru berhasil!'; */

        // $row = DB::table('m_kategori')->where('kategori_kode', 'SNK')->update(['kategori_nama' => 'Camillan']);
        // return 'Update data berhasil. Jumlah data yang diupdate: ' . $row . ' baris';

        // $row = DB::table('m_kategori')->where('kategori_kode', 'SNK')->delete();
        // return 'Delete data berhasil. Jumlah data yang dihapus: ' . $row . ' baris';

        $data = DB::table('m_kategori')->get();
        return view('kategori', ['data' => $data]);
    }
}
