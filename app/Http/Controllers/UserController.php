<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
<<<<<<< Updated upstream
use App\Models\LevelModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
        public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        $level = LevelModel::all(); // ambil data level untuk filter level

        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

        // Ambil data user dalam bentuk json untuk datatables
        public function list(Request $request)
    {
        // 1. Tambahkan 'avatar' di bagian select
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id', 'avatar')
            ->with('level');

        // Filter data user berdasarkan level_id
        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users)
            ->addIndexColumn()
            // 2. Tambahkan kolom avatar di sini
            ->addColumn('avatar', function ($user) {
                return '<img src="' . $user->avatar . '" height="30" class="img-circle">';
            })
            ->addColumn('aksi', function ($user) {
                $btn  = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            // 3. Masukkan 'avatar' ke dalam rawColumns agar tag <img> terbaca sebagai HTML
            ->rawColumns(['aksi', 'avatar']) 
            ->make(true);
    }
    // public function list(Request $request)
    // {
    //     $users = UserModel::select('user_id', 'username', 'nama', 'level_id', 'avatar')
    //         ->with('level');

    //     // Filter data user berdasarkan level_id
    //     if ($request->level_id) {
    //         $users->where('level_id', $request->level_id);
    //     }

    //     return DataTables::of($users)
    //         ->addIndexColumn()
    //         ->addColumn('avatar', function ($user) {
    //             if ($user->avatar && Storage::exists('public/photos/' . $user->avatar)) {
    //                 $url = asset('storage/photos/' . $user->avatar);
    //             } else {
    //                 // Solusi otomatis: generate gambar inisial berdasarkan nama
    //                 $url = 'https://ui-avatars.com/api/?name=' . urlencode($user->nama) . '&background=random&color=fff';
    //             }
                
    //             return '<img src="' . $url . '" height="30" class="img-circle" alt="User Image">';
    //         })
    //         ->addColumn('aksi', function ($user) {
    //             $btn  = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btn-sm">Detail</a> ';
    //             $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
    //             $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
    //             return $btn;
    //         })
    //         ->rawColumns(['aksi', 'avatar']) 
    //         ->make(true);
    // }

    // Menampilkan halaman form tambah user Ajax
    public function create_ajax()
    {
        $level = LevelModel::select('level_id', 'level_nama')->get();
        return view('user.create_ajax')->with('level', $level);
    }

    // Menyimpan data user baru Ajax
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:m_user,username',
                'nama'     => 'required|string|max:100',
                'password' => 'required|min:5',
                'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Validasi gambar
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $input = $request->all();
            
            // Proses Upload File
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                // Simpan dengan nama unik (hash) ke folder 'public/photos'
                $filename = $file->hashName(); 
                $file->storeAs('photos', $filename, 'public');
                $input['avatar'] = $filename;
            }

            $input['password'] = Hash::make($request->password);
            UserModel::create($input);

            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan'
            ]);
        }
        return redirect('/');
    }

    // Menampilkan halaman form edit user ajax
    public function edit_ajax(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::select('level_id', 'level_nama')->get();

        return view('user.edit_ajax', ['user' => $user, 'level' => $level]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:m_user,username,'.$id.',user_id',
                'nama'     => 'required|string|max:100',
                'password' => 'nullable|min:5',
                'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Validasi gambar
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $user = UserModel::find($id);
            if ($user) {
                $input = $request->all();

                // Proses Upload File Baru
                if ($request->hasFile('avatar')) {
                    $file = $request->file('avatar');
                    $filename = $file->hashName(); 
                    $file->storeAs('photos', $filename, 'public');
                    $input['avatar'] = $filename;

                    // Hapus foto lama jika ada (agar tidak menuhin server)
                    // Mengambil nama file asli dari database (bukan accessor)
                    $oldAvatar = $user->getRawOriginal('avatar');
                    if ($oldAvatar && Storage::exists('public/photos/' . $oldAvatar)) {
                        Storage::delete('public/photos/' . $oldAvatar);
                    }
                }

                if ($request->password) {
                    $input['password'] = Hash::make($request->password);
                } else {
                    unset($input['password']);
                }

                $user->update($input);
                return response()->json(['status' => true, 'message' => 'Data berhasil diupdate']);
            }
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $user = UserModel::find($id);
        return view('user.confirm_ajax', ['user' => $user]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $user = UserModel::find($id);
            if ($user) {
                $user->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    // --- FITUR IMPORT EXCEL ---
    public function import()
    {
        return view('user.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_user' => ['required', 'mimes:xlsx,xls', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_user');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);
            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) {
                        $insert[] = [
                            'level_id' => $value['A'],
                            'username' => $value['B'],
                            'nama' => $value['C'],
                            'password' => Hash::make($value['D']), // Hash password manual
                            'created_at' => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    UserModel::insertOrIgnore($insert);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/');
    }

    // --- FITUR EXPORT EXCEL ---
    public function export_excel()
    {
        $user = UserModel::select('level_id', 'username', 'nama', 'password')
            ->orderBy('level_id')
            ->with('level')
            ->get();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Username');
        $sheet->setCellValue('C1', 'Nama Pengguna');
        $sheet->setCellValue('D1', 'Level Pengguna');
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;
        foreach ($user as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->username);
            $sheet->setCellValue('C' . $baris, $value->nama);
            $sheet->setCellValue('D' . $baris, $value->level->level_nama);
            $baris++;
            $no++;
        }
        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $sheet->setTitle('Data User');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data User ' . date('Y-m-d H:i:s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
        exit;
    }

    // --- FITUR EXPORT PDF ---
    public function export_pdf()
    {
        $user = UserModel::select('level_id', 'username', 'nama')
            ->orderBy('level_id')
            ->orderBy('username')
            ->with('level')
            ->get();
        $pdf = Pdf::loadView('user.export_pdf', ['user' => $user]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", false);
        $pdf->render();
        return $pdf->stream('Data User ' . date('Y-m-d H:i:s') . '.pdf');
    }

    // PEMBATAS

    // Menampilkan halaman form tambah user
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah user baru'
        ];

        $level = LevelModel::all(); // ambil data level untuk ditampilkan di form
        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    // Menyimpan data user baru
    public function store(Request $request)
    {
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama'     => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
            'password' => 'required|min:5',          // password harus diisi dan minimal 5 karakter
            'level_id' => 'required|integer'         // level_id harus diisi dan berupa angka
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama'     => $request->nama,
            'password' => bcrypt($request->password), // password dienkripsi sebelum disimpan
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }

    // Menampilkan detail user
    public function show(string $id)
    {
        $user = UserModel::with('level')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list'  => ['Home', 'User', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail user'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    // Menampilkan halaman form edit user
    public function edit(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list'  => ['Home', 'User', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit user'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    // Menyimpan perubahan data user
    public function update(Request $request, string $id)
    {
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter,
            // dan bernilai unik di tabel m_user kolom username KECUALI untuk user dengan id yang sedang diedit
            'username' => 'required|string|min:3|unique:m_user,username,'.$id.',user_id',
            'nama'     => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
            'password' => 'nullable|min:5',          // password bisa diisi (minimal 5 karakter) dan bisa tidak diisi
            'level_id' => 'required|integer'         // level_id harus diisi dan berupa angka
        ]);

        UserModel::find($id)->update([
            'username' => $request->username,
            'nama'     => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }

    // Menghapus data user
    public function destroy(string $id)
    {
        $check = UserModel::find($id);

        if (!$check) {  // untuk mengecek apakah data user dengan id yang dimaksud ada atau tidak
            return redirect('/user')->with('error', 'Data user tidak ditemukan');
        }

        try {
            UserModel::destroy($id); // Hapus data user

            return redirect('/user')->with('success', 'Data user berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }


    // public function index() {
    //     // DB::insert('insert into m_user (level_id, username, nama, password, created_at) values(?, ?, ?, ?, ?)', 
    //     //     [3, 'testinguser', 'User Test', bcrypt('password123'), now()]
    //     // );
    //     // return 'Insert data user baru berhasil';

    //     // $row = DB::update('update m_user set nama = ? where username = ?', 
    //     //     ['User Percobaan', 'testinguser']
    //     // );
    //     // return 'Update data user berhasil. Jumlah data yg di update: '.$row.' baris';

    //     // $row = DB::delete('delete from m_user where username = ?', 
    //     //     ['customer-1']
    //     // );
    //     // return 'Delete data user berhasil. Jumlah data yg dihapus: '.$row.' baris';

    //     // $data = DB::select('select * from m_user');
    //     // return view('user', ['data' => $data] );

    //     //  // tambah data user dengan Eloquent Model
    //     // $data = [
    //     //     'username' => 'customer-1',
    //     //     'nama' => 'pelanggan',
    //     //     'password' => Hash::make("12345"),
    //     //     'level_id' => 3
    //     // ];
    //     // UserModel::insert($data); // tambahkan data ke tabel m_user

    //     //  // tambah data user dengan Eloquent Model
    //     // $data = [
    //     //     'nama' => 'Pelanggan Pertama',
    //     // ];
    //     // UserModel::where('username', 'customer-1')->update($data); // update data user

    //     // // coba akses model UserModel
    //     // $user = UserModel::all(); // ambil semua data dari tabel m_user
    //     // return view('user', ['data' => $user]);

    //     // $data = [
    //     //     'level_id' => 2,
    //     //     'username' => 'manager_tiga',
    //     //     'nama' => 'Manager 3',
    //     //     'password' => Hash::make('12345')
    //     // ];
    //     // UserModel::create($data);

    //     // $user = UserModel::all();
    //     // return view('user', ['data' => $user]);

    //     // $user = UserModel::find(1);
    //     // return view('user', ['data' => $user]);

    //     // $user = UserModel::where('level_id', 1)->first();
    //     // return view('user', ['data' => $user]);

    //     // $user = UserModel::firstWhere('level_id', 1);
    //     // return view('user', ['data' => $user]);

    //     // $user = UserModel::findOr(1, ['username', 'nama'], function() {
    //     //     abort(404);
    //     // });

    //     // return view('user', ['data' => $user]);

    //     // $user = UserModel::findOr(20, ['username', 'nama'], function() {
    //     //     abort(404);
    //     // });

    //     // return view('user', ['data' => $user]);

    //     // $user = UserModel::findOrFail(1);
    //     // return view('user', ['data' => $user]);

    //     // $user = UserModel::where('username', 'manager9')->firstOrFail();
    //     // return view('user', ['data' => $user]);

    //     // $user = UserModel::where('level_id', 2)->count();
    //     // dd($user);
    //     // return view('user', ['data' => $user]);

    //     // $user = UserModel::where('level_id', 2)->count();
    //     // return view('user', ['data' => $user]);

    //     // $user = UserModel::firstOrCreate(
    //     //     [
    //     //         'username' => 'manager',
    //     //         'nama' => 'Manager',
    //     //     ],
    //     // );
    //     // return view('user', ['data' => $user]);

    //     // $user = UserModel::firstOrCreate(
    //     //     [
    //     //         'username' => 'manager22',
    //     //         'nama' => 'Manager Dua Dua',
    //     //         'password' => Hash::make('12345'),
    //     //         'level_id' => 2
    //     //     ],
    //     // );
    //     // return view('user', ['data' => $user]);

    //     // $user = UserModel::firstOrNew(
    //     //     [
    //     //         'username' => 'manager',
    //     //         'nama' => 'Manager',
    //     //     ],
    //     // );
    //     // return view('user', ['data' => $user]);

    //     // $user = UserModel::firstOrNew(
    //     //     [
    //     //         'username' => 'manager33',
    //     //         'nama' => 'Manager Tiga Tiga',
    //     //         'password' => Hash::make('12345'),
    //     //         'level_id' => 2
    //     //     ],
    //     // );
    //     // return view('user', ['data' => $user]);

    //     // $user = UserModel::firstOrNew(
    //     //     [
    //     //         'username' => 'manager33',
    //     //         'nama' => 'Manager Tiga Tiga',
    //     //         'password' => Hash::make('12345'),
    //     //         'level_id' => 2
    //     //     ],
    //     // );
    //     // $user->save();
    //     // return view('user', ['data' => $user]);

    //     // $user = UserModel::create([
    //     //     'username' => 'manager55',
    //     //     'nama' => 'Manager55',
    //     //     'password' => Hash::make('12345'),
    //     //     'level_id' => 2,
    //     // ]);

    //     // $user->username = 'manager56';

    //     // $user->isDirty();                 // true
    //     // $user->isDirty('username');       // true
    //     // $user->isDirty(['nama']);         // false
    //     // $user->isDirty(['nama', 'username']); // true

    //     // $user->isClean();                 // false
    //     // $user->isClean('username');       // false
    //     // $user->isClean('nama');           // true
    //     // $user->isClean(['nama', 'username']); // false

    //     // $user->save();

    //     // $user->isDirty();                 // false
    //     // $user->isClean();                 // true
    //     // dd($user->isDirty());

    //     // $user = UserModel::create([
    //     //     'username' => 'managerr1',
    //     //     'nama' => 'Manager11',
    //     //     'password' => Hash::make('12345'),
    //     //     'level_id' => 2,
    //     // ]);

    //     // $user->username = 'managerr12';

    //     // $user->save();

    //     // $user->wasChanged(); // true
    //     // $user->wasChanged('username'); // true
    //     // $user->wasChanged(['username', 'level_id']); // true
    //     // $user->wasChanged('nama'); // false
    //     // $user->wasChanged(['nama', 'username']); // true

    //     // $user = UserModel::create([
    //     //     'username' => 'managerr1',
    //     //     'nama' => 'Manager11',
    //     //     'password' => Hash::make('12345'),
    //     //     'level_id' => 2,
    //     // ]);

    //     // $user->username = 'managerr13'; // tidak bisa diisi managerr12 karena bentrok dengan sebelumnya

    //     // $user->save();

    //     // $user->wasChanged(); // true
    //     // $user->wasChanged('username'); // true
    //     // $user->wasChanged(['username', 'level_id']); // true
    //     // $user->wasChanged('nama'); // false
    //     // dd($user->wasChanged(['nama', 'username'])); // true

    //     // $user = UserModel::all();
    //     // return view('user', ['data' => $user]);

    //     // $user = UserModel::with('level')->get();
    //     // dd($user);

    //     $user = UserModel::with('level')->get();
    //     return view('user', ['data' => $user]);  
    // }

    // // public function tambah(){
    // //     return view('user_tambah');
    // // }

    // // public function tambah_simpan(Request $request){
    // //     UserModel::create([
    // //         'username' => $request->username,
    // //         'nama' => $request->nama,
    // //         'password' => Hash::make('$request->password'),
    // //         'level_id' => $request->level_id
    // //     ]);

    // //     return redirect('/user');
    // // }

    // // public function ubah($id){
    // //     $user = UserModel::find($id);
    // //     return view('user_ubah', ['data' => $user]);
    // // }

    // // public function ubah_simpan($id, Request $request){
    // //     $user = UserModel::find($id);

    // //     $user->username = $request->username;
    // //     $user->nama = $request->nama;
    // //     $user->password = Hash::make('$request->password');
    // //     $user->level_id = $request->level_id;

    // //     $user->save();

    // //     return redirect('/user');
    // // }

    // // public function hapus($id){
    // //     $user = UserModel::find($id);
    // //     $user->delete();

    // //     return redirect('/user');
    // // }
=======

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
        //     ['customer-1']
        // );
        // return 'Delete data user berhasil. Jumlah data yg dihapus: '.$row.' baris';

        // $data = DB::select('select * from m_user');
        // return view('user', ['data' => $data] );

        //  // tambah data user dengan Eloquent Model
        // $data = [
        //     'username' => 'customer-1',
        //     'nama' => 'pelanggan',
        //     'password' => Hash::make("12345"),
        //     'level_id' => 3
        // ];
        // UserModel::insert($data); // tambahkan data ke tabel m_user

        //  // tambah data user dengan Eloquent Model
        // $data = [
        //     'nama' => 'Pelanggan Pertama',
        // ];
        // UserModel::where('username', 'customer-1')->update($data); // update data user

        // // coba akses model UserModel
        // $user = UserModel::all(); // ambil semua data dari tabel m_user
        // return view('user', ['data' => $user]);

        // $data = [
        //     'level_id' => 2,
        //     'username' => 'manager_tiga',
        //     'nama' => 'Manager 3',
        //     'password' => Hash::make('12345')
        // ];
        // UserModel::create($data);

        // $user = UserModel::all();
        // return view('user', ['data' => $user]);

        // $user = UserModel::find(1);
        // return view('user', ['data' => $user]);

        // $user = UserModel::where('level_id', 1)->first();
        // return view('user', ['data' => $user]);

        // $user = UserModel::firstWhere('level_id', 1);
        // return view('user', ['data' => $user]);

        // $user = UserModel::findOr(1, ['username', 'nama'], function() {
        //     abort(404);
        // });

        // return view('user', ['data' => $user]);

        // $user = UserModel::findOr(20, ['username', 'nama'], function() {
        //     abort(404);
        // });

        // return view('user', ['data' => $user]);

        // $user = UserModel::findOrFail(1);
        // return view('user', ['data' => $user]);

        // $user = UserModel::where('username', 'manager9')->firstOrFail();
        // return view('user', ['data' => $user]);

        // $user = UserModel::where('level_id', 2)->count();
        // dd($user);
        // return view('user', ['data' => $user]);

        // $user = UserModel::where('level_id', 2)->count();
        // return view('user', ['data' => $user]);

        // $user = UserModel::firstOrCreate(
        //     [
        //         'username' => 'manager',
        //         'nama' => 'Manager',
        //     ],
        // );
        // return view('user', ['data' => $user]);

        // $user = UserModel::firstOrCreate(
        //     [
        //         'username' => 'manager22',
        //         'nama' => 'Manager Dua Dua',
        //         'password' => Hash::make('12345'),
        //         'level_id' => 2
        //     ],
        // );
        // return view('user', ['data' => $user]);

        // $user = UserModel::firstOrNew(
        //     [
        //         'username' => 'manager',
        //         'nama' => 'Manager',
        //     ],
        // );
        // return view('user', ['data' => $user]);

        // $user = UserModel::firstOrNew(
        //     [
        //         'username' => 'manager33',
        //         'nama' => 'Manager Tiga Tiga',
        //         'password' => Hash::make('12345'),
        //         'level_id' => 2
        //     ],
        // );
        // return view('user', ['data' => $user]);

        // $user = UserModel::firstOrNew(
        //     [
        //         'username' => 'manager33',
        //         'nama' => 'Manager Tiga Tiga',
        //         'password' => Hash::make('12345'),
        //         'level_id' => 2
        //     ],
        // );
        // $user->save();
        // return view('user', ['data' => $user]);

        // $user = UserModel::create([
        //     'username' => 'manager55',
        //     'nama' => 'Manager55',
        //     'password' => Hash::make('12345'),
        //     'level_id' => 2,
        // ]);

        // $user->username = 'manager56';

        // $user->isDirty();                 // true
        // $user->isDirty('username');       // true
        // $user->isDirty(['nama']);         // false
        // $user->isDirty(['nama', 'username']); // true

        // $user->isClean();                 // false
        // $user->isClean('username');       // false
        // $user->isClean('nama');           // true
        // $user->isClean(['nama', 'username']); // false

        // $user->save();

        // $user->isDirty();                 // false
        // $user->isClean();                 // true
        // dd($user->isDirty());

        // $user = UserModel::create([
        //     'username' => 'managerr1',
        //     'nama' => 'Manager11',
        //     'password' => Hash::make('12345'),
        //     'level_id' => 2,
        // ]);

        // $user->username = 'managerr12';

        // $user->save();

        // $user->wasChanged(); // true
        // $user->wasChanged('username'); // true
        // $user->wasChanged(['username', 'level_id']); // true
        // $user->wasChanged('nama'); // false
        // $user->wasChanged(['nama', 'username']); // true

        // $user = UserModel::create([
        //     'username' => 'managerr1',
        //     'nama' => 'Manager11',
        //     'password' => Hash::make('12345'),
        //     'level_id' => 2,
        // ]);

        // $user->username = 'managerr13'; // tidak bisa diisi managerr12 karena bentrok dengan sebelumnya

        // $user->save();

        // $user->wasChanged(); // true
        // $user->wasChanged('username'); // true
        // $user->wasChanged(['username', 'level_id']); // true
        // $user->wasChanged('nama'); // false
        // dd($user->wasChanged(['nama', 'username'])); // true

        // $user = UserModel::all();
        // return view('user', ['data' => $user]);

        // $user = UserModel::with('level')->get();
        // dd($user);

        $user = UserModel::with('level')->get();
        return view('user', ['data' => $user]);  
    }

    // public function tambah(){
    //     return view('user_tambah');
    // }

    // public function tambah_simpan(Request $request){
    //     UserModel::create([
    //         'username' => $request->username,
    //         'nama' => $request->nama,
    //         'password' => Hash::make('$request->password'),
    //         'level_id' => $request->level_id
    //     ]);

    //     return redirect('/user');
    // }

    // public function ubah($id){
    //     $user = UserModel::find($id);
    //     return view('user_ubah', ['data' => $user]);
    // }

    // public function ubah_simpan($id, Request $request){
    //     $user = UserModel::find($id);

    //     $user->username = $request->username;
    //     $user->nama = $request->nama;
    //     $user->password = Hash::make('$request->password');
    //     $user->level_id = $request->level_id;

    //     $user->save();

    //     return redirect('/user');
    // }

    // public function hapus($id){
    //     $user = UserModel::find($id);
    //     $user->delete();

    //     return redirect('/user');
    // }
>>>>>>> Stashed changes
}
