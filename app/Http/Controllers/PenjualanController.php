<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
<<<<<<< HEAD
use App\Models\PenjualanModel;
use App\Models\UserModel;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PenjualanDetailModel;
use App\Models\BarangModel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory; // Excel
use Barryvdh\DomPDF\Facade\Pdf;         // PDF
=======
>>>>>>> 8ff7ed2912a5ba3f923844970145ce6766b169aa

class PenjualanController extends Controller
{
    public function index() {
<<<<<<< HEAD
        $breadcrumb = (object) ['title' => 'Transaksi Penjualan', 'list' => ['Home', 'Penjualan']];
        $page = (object) ['title' => 'Daftar transaksi penjualan'];
        $activeMenu = 'penjualan';
        return view('penjualan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request) {
        $penjualans = PenjualanModel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')->with('user');
        return DataTables::of($penjualans)
            ->addIndexColumn()
            ->addColumn('aksi', function ($penjualan) {
            $btn = '<a href="'.url('/penjualan/' . $penjualan->penjualan_id).'" class="btn btn-info btn-sm">Detail</a> ';
            $btn .= '<button onclick="modalAction(\''.url('/penjualan/' . $penjualan->penjualan_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
            $btn .= '<button onclick="modalAction(\''.url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])->make(true);
    }

    public function create_ajax() {
        $user = UserModel::select('user_id', 'nama')->get();
        $barang = BarangModel::select('barang_id', 'barang_nama', 'harga_jual')->get();
        return view('penjualan.create_ajax', ['user' => $user, 'barang' => $barang]);
    }

    public function store_ajax(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'user_id' => 'required|integer',
                'pembeli' => 'required|string|max:100',
                'penjualan_kode' => 'required|unique:t_penjualan,penjualan_kode',
                'penjualan_tanggal' => 'required|date',
                'barang_id' => 'required|array',
                'jumlah' => 'required|array'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Validasi gagal.', 'msgField' => $validator->errors()]);
            }

            DB::beginTransaction();
            try {
                $penjualan = PenjualanModel::create([
                    'user_id' => $request->user_id,
                    'pembeli' => $request->pembeli,
                    'penjualan_kode' => $request->penjualan_kode,
                    'penjualan_tanggal' => $request->penjualan_tanggal
                ]);

                foreach($request->barang_id as $key => $val) {
                    $barang = BarangModel::find($val);
                    PenjualanDetailModel::create([
                        'penjualan_id' => $penjualan->penjualan_id,
                        'barang_id' => $val,
                        'harga' => $barang->harga_jual,
                        'jumlah' => $request->jumlah[$key]
                    ]);
                }
                DB::commit();
                return response()->json(['status' => true, 'message' => 'Transaksi berhasil disimpan']);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['status' => false, 'message' => 'Terjadi kesalahan sistem']);
            }
        }
        return redirect('/');
    }

    public function edit_ajax(string $id)
    {
        // Load data penjualan beserta detail barangnya
        $penjualan = PenjualanModel::with('detail.barang')->find($id);
        $user = UserModel::select('user_id', 'nama')->get();
        $barang = BarangModel::select('barang_id', 'barang_nama', 'harga_jual')->get();

        if ($penjualan) {
            return view('penjualan.edit_ajax', [
                'penjualan' => $penjualan,
                'user' => $user,
                'barang' => $barang
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    /**
     * Menyimpan perubahan data penjualan via Ajax
     */
    public function update_ajax(Request $request, $id)
    {
        // Cek apakah request datang dari Ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'user_id'           => 'required|integer',
                'pembeli'           => 'required|string|max:100',
                'penjualan_tanggal' => 'required|date',
                'detail_id'         => 'required|array', // ID detail untuk update
                'jumlah'            => 'required|array',    // Array jumlah barang
                'jumlah.*'          => 'required|integer|min:1'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $penjualan = PenjualanModel::find($id);
            if (!$penjualan) {
                return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
            }

            DB::beginTransaction();
            try {
                // 1. Update Header Penjualan
                $penjualan->update([
                    'user_id'           => $request->user_id,
                    'pembeli'           => $request->pembeli,
                    'penjualan_tanggal' => $request->penjualan_tanggal,
                ]);

                // 2. Update Detail Penjualan (Looping berdasarkan array detail_id)
                foreach ($request->detail_id as $key => $dtl_id) {
                    $detail = PenjualanDetailModel::find($dtl_id);
                    if ($detail) {
                        $detail->update([
                            'jumlah' => $request->jumlah[$key]
                        ]);
                    }
                }

                DB::commit();
                return response()->json([
                    'status'  => true,
                    'message' => 'Data transaksi berhasil diperbarui'
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'status'  => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id) {
        $penjualan = PenjualanModel::find($id);
        return view('penjualan.confirm_ajax', ['penjualan' => $penjualan]);
    }

    public function delete_ajax(Request $request, $id) {
        if ($request->ajax() || $request->wantsJson()) {
            DB::beginTransaction();
            try {
                PenjualanDetailModel::where('penjualan_id', $id)->delete();
                PenjualanModel::destroy($id);
                DB::commit();
                return response()->json(['status' => true, 'message' => 'Data transaksi berhasil dihapus']);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['status' => false, 'message' => 'Gagal menghapus data']);
            }
        }
        return redirect('/');
    }

    // --- IMPORT EXCEL ---
    public function import()
    {
        return view('penjualan.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_penjualan' => ['required', 'mimes:xlsx,xls', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_penjualan');
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
                            'user_id'           => $value['A'],
                            'pembeli'           => $value['B'],
                            'penjualan_kode'    => $value['C'],
                            'penjualan_tanggal' => $value['D'],
                            'created_at'        => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    PenjualanModel::insertOrIgnore($insert);
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

    // --- EXPORT EXCEL ---
    public function export_excel()
    {
        $penjualan = PenjualanModel::with('user')->orderBy('penjualan_tanggal', 'desc')->get();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Penjualan');
        $sheet->setCellValue('C1', 'Pembeli');
        $sheet->setCellValue('D1', 'Kasir');
        $sheet->setCellValue('E1', 'Tanggal');

        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;
        foreach ($penjualan as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->penjualan_kode);
            $sheet->setCellValue('C' . $baris, $value->pembeli);
            $sheet->setCellValue('D' . $baris, $value->user->nama);
            $sheet->setCellValue('E' . $baris, $value->penjualan_tanggal);
            $baris++;
            $no++;
        }

        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Penjualan');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Penjualan ' . date('Y-m-d H:i:s') . '.xlsx';

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

    // --- EXPORT PDF ---
    public function export_pdf()
    {
        $penjualan = PenjualanModel::with('user')->orderBy('penjualan_tanggal', 'desc')->get();
        $pdf = Pdf::loadView('penjualan.export_pdf', ['penjualan' => $penjualan]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", false);
        $pdf->render();

        return $pdf->stream('Data Penjualan ' . date('Y-m-d H:i:s') . '.pdf');
    }

//     public function index() {
//         $breadcrumb = (object) ['title' => 'Transaksi Penjualan', 'list' => ['Home', 'Penjualan']];
//         $page = (object) ['title' => 'Daftar transaksi penjualan'];
//         $activeMenu = 'penjualan';
//         $user = UserModel::all(); // Untuk filter kasir
//         return view('penjualan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
//     }

//     // Ambil data untuk DataTable
//     public function list(Request $request) {
//     $penjualans = PenjualanModel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
//                 ->with('user');

//     return DataTables::of($penjualans)
//         ->addIndexColumn()
//         ->addColumn('aksi', function ($penjualan) {
//             $btn  = '<a href="'.url('/penjualan/' . $penjualan->penjualan_id).'" class="btn btn-info btn-sm">Detail</a> ';
//             // Tambahkan baris tombol edit di bawah ini
//             $btn .= '<a href="'.url('/penjualan/' . $penjualan->penjualan_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
//             $btn .= '<form class="d-inline-block" method="POST" action="'. url('/penjualan/'.$penjualan->penjualan_id).'">'. 
//                     csrf_field() . method_field('DELETE') . 
//                     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Hapus transaksi ini?\');">Hapus</button></form>';
//             return $btn;
//         })
//         ->rawColumns(['aksi'])
//         ->make(true);
//     }

//     public function create() {
//         $breadcrumb = (object) ['title' => 'Tambah Penjualan', 'list' => ['Home', 'Penjualan', 'Tambah']];
//         $page = (object) ['title' => 'Tambah transaksi baru'];
//         $user = UserModel::all(); 
//         $barang = BarangModel::all(); // Diperlukan untuk memilih item barang
//         $activeMenu = 'penjualan';
//         return view('penjualan.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'barang' => $barang, 'activeMenu' => $activeMenu]);
//     }

//     // Simpan Transaksi (Header & Detail) menggunakan Database Transaction
//     public function store(Request $request) {
//         $request->validate([
//             'user_id'           => 'required|integer',
//             'pembeli'           => 'required|string|max:50',
//             'penjualan_kode'    => 'required|string|unique:t_penjualan,penjualan_kode',
//             'penjualan_tanggal' => 'required|date',
//             'barang_id'         => 'required|array', // Array input dari form
//             'jumlah'            => 'required|array',
//         ]);

//         DB::beginTransaction();
//         try {
//             // 1. Simpan ke t_penjualan
//             $penjualan = PenjualanModel::create([
//                 'user_id'           => $request->user_id,
//                 'pembeli'           => $request->pembeli,
//                 'penjualan_kode'    => $request->penjualan_kode,
//                 'penjualan_tanggal' => $request->penjualan_tanggal,
//             ]);

//             // 2. Simpan ke t_penjualan_detail
//             foreach ($request->barang_id as $key => $id_barang) {
//                 $barang = BarangModel::find($id_barang);
//                 PenjualanDetailModel::create([
//                     'penjualan_id' => $penjualan->penjualan_id,
//                     'barang_id'    => $id_barang,
//                     'harga'        => $barang->harga_jual, // Ambil harga saat ini dari m_barang
//                     'jumlah'       => $request->jumlah[$key],
//                 ]);
//             }

//             DB::commit();
//             return redirect('/penjualan')->with('success', 'Transaksi berhasil disimpan');
//         } catch (\Exception $e) {
//             DB::rollback();
//             return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
//         }
//     }

    public function show($id) {
        // Load relasi user dan detail.barang secara eager loading
        $penjualan = PenjualanModel::with(['user', 'detail.barang'])->find($id);
        
        $breadcrumb = (object) ['title' => 'Detail Penjualan', 'list' => ['Home', 'Penjualan', 'Detail']];
        $activeMenu = 'penjualan';
        $page = (object) ['title' => 'Detail transaksi penjualan'];

        if (!$penjualan) {
            return redirect('/penjualan')->with('error', 'Data tidak ditemukan');
        }

        return view('penjualan.show', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'penjualan' => $penjualan, 
            'activeMenu' => $activeMenu
        ]);
    }

//     public function edit($id) {
//     // Ambil data penjualan beserta detail dan barangnya
//     $penjualan = PenjualanModel::with('detail.barang')->find($id);
//     $user = UserModel::all();
//     $barang = BarangModel::all(); // Diperlukan jika ingin mengubah item barang

//     $breadcrumb = (object) ['title' => 'Edit Penjualan', 'list' => ['Home', 'Penjualan', 'Edit']];
//     $page = (object) ['title' => 'Edit transaksi penjualan'];
//     $activeMenu = 'penjualan';

//     return view('penjualan.edit', [
//         'breadcrumb' => $breadcrumb, 
//         'page' => $page, 
//         'penjualan' => $penjualan, 
//         'user' => $user, 
//         'barang' => $barang,
//         'activeMenu' => $activeMenu
//     ]);
// }

// public function update(Request $request, $id) {
//     $request->validate([
//         'user_id'           => 'required|integer',
//         'pembeli'           => 'required|string|max:50',
//         'penjualan_tanggal' => 'required|date',
//         'detail_id'         => 'required|array',
//         'barang_id'         => 'required|array',
//         'jumlah'            => 'required|array',
//     ]);

//     DB::beginTransaction();
//     try {
//         // 1. Update Header Penjualan
//         $penjualan = PenjualanModel::find($id);
//         $penjualan->update([
//             'user_id'           => $request->user_id,
//             'pembeli'           => $request->pembeli,
//             'penjualan_tanggal' => $request->penjualan_tanggal,
//         ]);

//         // 2. Update Detail Penjualan
//         foreach ($request->detail_id as $key => $dtl_id) {
//             $detail = PenjualanDetailModel::find($dtl_id);
//             if ($detail) {
//                 $detail->update([
//                     'barang_id' => $request->barang_id[$key],
//                     'jumlah'    => $request->jumlah[$key],
//                     // Harga biasanya tidak diupdate otomatis agar history harga saat transaksi tetap terjaga
//                 ]);
//             }
//         }

//         DB::commit();
//         return redirect('/penjualan')->with('success', 'Transaksi berhasil diubah');
//     } catch (\Exception $e) {
//         DB::rollback();
//         return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
//     }
//     }

//     public function destroy($id) {
//         $check = PenjualanModel::find($id);
//         if (!$check) {
//             return redirect('/penjualan')->with('error', 'Data tidak ditemukan');
//         }

//         try {
//             // Karena menggunakan Eloquent, detail bisa dihapus otomatis jika ada cascade
//             // Atau hapus manual detailnya dulu
//             PenjualanDetailModel::where('penjualan_id', $id)->delete();
//             PenjualanModel::destroy($id);

//             return redirect('/penjualan')->with('success', 'Data berhasil dihapus');
//         } catch (\Exception $e) {
//             return redirect('/penjualan')->with('error', 'Gagal menghapus data');
//         }
//     }

    // public function index() {
    
    //     // DB::insert('insert into t_penjualan (user_id, pembeli, penjualan_kode, penjualan_tanggal, created_at) values(?, ?, ?, ?, ?)', 
    //     //     [3, 'Budi Santoso', 'PJL011', now(), now()]
    //     // );
    //     // return 'Insert data penjualan baru berhasil';

    //     // $row = DB::update('update t_penjualan set pembeli = ? where penjualan_kode = ?', 
    //     //     ['Budi Santoso (Member)', 'PJL011']
    //     // );
    //     // return 'Update data penjualan berhasil. Jumlah data yg di update: '.$row.' baris';

    //     // $row = DB::delete('delete from t_penjualan where penjualan_kode = ?', 
    //     //     ['PJL011']
    //     // );
    //     // return 'Delete data penjualan berhasil. Jumlah data yg dihapus: '.$row.' baris';

    //     $data = DB::select('select * from t_penjualan');
    //     return view('penjualan', ['data' => $data] );
    // }
=======
    
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
>>>>>>> 8ff7ed2912a5ba3f923844970145ce6766b169aa
}
