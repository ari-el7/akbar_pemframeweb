<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index() {
        // DB::insert('insert into m_user (level_id, username, nama, password, created_at) values(?, ?, ?, ?, ?)', 
        //     [3, 'testinguser', 'User Test', bcrypt('password123'), now()]
        // );
        // return 'Insert data user baru berhasil';

        // $row = DB::update('update m_user set nama = ? where username = ?', 
        //     ['User Percobaan', 'testinguser']
        // );
        // return 'Update data user berhasil. Jumlah data yg di update: '.$row.' baris';

        // $row = DB::delete('delete from m_user where username = ?', 
        //     ['testinguser']
        // );
        // return 'Delete data user berhasil. Jumlah data yg dihapus: '.$row.' baris';

        $data = DB::select('select * from m_user');
        return view('user', ['data' => $data] );
    }
}
