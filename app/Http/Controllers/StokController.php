<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
<<<<<<< HEAD
use App\Models\StokModel;
use App\Models\SupplierModel;
use App\Models\BarangModel;
use App\Models\UserModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory; // Excel
use Barryvdh\DomPDF\Facade\Pdf;         // PDF
=======
>>>>>>> 8ff7ed2912a5ba3f923844970145ce6766b169aa

class StokController extends Controller
{
    public function index() {
<<<<<<< HEAD
        $breadcrumb = (object) ['title' => 'Daftar Stok Barang', 'list' => ['Home', 'Stok']];
        $page = (object) ['title' => 'Daftar stok barang yang tersimpan dalam sistem'];
        $activeMenu = 'stok';
        return view('stok.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request) {
        $stoks = StokModel::select('stok_id', 'supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
                    ->with(['supplier', 'barang', 'user']);

        return DataTables::of($stoks)
            ->addIndexColumn()
            ->addColumn('aksi', function ($stok) {
                $btn = '<a href="'.url('/stok/' . $stok->stok_id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax() {
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $user = UserModel::select('user_id', 'nama')->get();
        return view('stok.create_ajax', ['supplier' => $supplier, 'barang' => $barang, 'user' => $user]);
    }

    public function store_ajax(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_id'  => 'required|integer',
                'barang_id'    => 'required|integer',
                'user_id'      => 'required|integer',
                'stok_tanggal' => 'required|date',
                'stok_jumlah'  => 'required|integer'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }
            StokModel::create($request->all());
            return response()->json(['status' => true, 'message' => 'Data stok berhasil disimpan']);
        }
        return redirect('/');
    }

    public function edit_ajax(string $id) {
        $stok = StokModel::find($id);
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $user = UserModel::select('user_id', 'nama')->get();
        return view('stok.edit_ajax', ['stok' => $stok, 'supplier' => $supplier, 'barang' => $barang, 'user' => $user]);
    }

    public function update_ajax(Request $request, $id) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_id'  => 'required|integer',
                'barang_id'    => 'required|integer',
                'user_id'      => 'required|integer',
                'stok_tanggal' => 'required|date',
                'stok_jumlah'  => 'required|integer'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Validasi gagal.', 'msgField' => $validator->errors()]);
            }
            $check = StokModel::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json(['status' => true, 'message' => 'Data berhasil diupdate']);
            }
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id) {
        $stok = StokModel::with('barang')->find($id);
        return view('stok.confirm_ajax', ['stok' => $stok]);
    }

    public function delete_ajax(Request $request, $id) {
        if ($request->ajax() || $request->wantsJson()) {
            $stok = StokModel::find($id);
            if ($stok) {
                $stok->delete();
                return response()->json(['status' => true, 'message' => 'Data berhasil dihapus']);
            }
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
        }
        return redirect('/');
    }

    // --- IMPORT EXCEL ---
    public function import()
    {
        return view('stok.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_stok' => ['required', 'mimes:xlsx,xls', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_stok');
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
                            'barang_id'    => $value['A'],
                            'user_id'      => $value['B'],
                            'stok_tanggal' => $value['C'],
                            'stok_jumlah'  => $value['D'],
                            'created_at'   => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    StokModel::insertOrIgnore($insert);
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
        $stok = StokModel::with(['barang', 'user'])->orderBy('stok_tanggal', 'desc')->get();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Barang');
        $sheet->setCellValue('C1', 'Nama User');
        $sheet->setCellValue('D1', 'Tanggal Stok');
        $sheet->setCellValue('E1', 'Jumlah');

        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;
        foreach ($stok as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->barang->barang_nama);
            $sheet->setCellValue('C' . $baris, $value->user->nama);
            $sheet->setCellValue('D' . $baris, $value->stok_tanggal);
            $sheet->setCellValue('E' . $baris, $value->stok_jumlah);
            $baris++;
            $no++;
        }

        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Stok');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Stok ' . date('Y-m-d H:i:s') . '.xlsx';

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
        $stok = StokModel::with(['barang', 'user'])->orderBy('stok_tanggal', 'desc')->get();
        $pdf = Pdf::loadView('stok.export_pdf', ['stok' => $stok]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", false);
        $pdf->render();

        return $pdf->stream('Data Stok ' . date('Y-m-d H:i:s') . '.pdf');
    }
    
    // public function index() {
    //     $breadcrumb = (object) ['title' => 'Daftar Stok', 'list' => ['Home', 'Stok']];
    //     $page = (object) ['title' => 'Daftar stok barang'];
    //     $activeMenu = 'stok';
    //     $supplier = SupplierModel::all(); // Untuk filter (opsional)
    //     return view('stok.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier, 'activeMenu' => $activeMenu]);
    // }

    // public function list(Request $request) {
    //     $stoks = StokModel::select('stok_id', 'supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
    //                 ->with(['supplier', 'barang', 'user']);

    //     // Filter berdasarkan supplier_id (jika ada)
    //     if ($request->supplier_id) {
    //         $stoks->where('supplier_id', $request->supplier_id);
    //     }

    //     return DataTables::of($stoks)
    //         ->addIndexColumn()
    //         ->addColumn('aksi', function ($stok) {
    //             $btn  = '<a href="'.url('/stok/' . $stok->stok_id).'" class="btn btn-info btn-sm">Detail</a> ';
    //             $btn .= '<a href="'.url('/stok/' . $stok->stok_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
    //             $btn .= '<form class="d-inline-block" method="POST" action="'. url('/stok/'.$stok->stok_id).'">'. 
    //                     csrf_field() . method_field('DELETE') . 
    //                     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Hapus data ini?\');">Hapus</button></form>';
    //             return $btn;
    //         })
    //         ->rawColumns(['aksi'])
    //         ->make(true);
    // }

    // public function create() {
    //     $breadcrumb = (object) ['title' => 'Tambah Stok', 'list' => ['Home', 'Stok', 'Tambah']];
    //     $page = (object) ['title' => 'Tambah stok baru'];
    //     $supplier = SupplierModel::all();
    //     $barang = BarangModel::all();
    //     $user = UserModel::all();
    //     $activeMenu = 'stok';
    //     return view('stok.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier, 'barang' => $barang, 'user' => $user, 'activeMenu' => $activeMenu]);
    // }

    // public function store(Request $request) {
    //     $request->validate([
    //         'supplier_id' => 'required|integer',
    //         'barang_id'   => 'required|integer',
    //         'user_id'     => 'required|integer',
    //         'stok_tanggal'=> 'required|date',
    //         'stok_jumlah' => 'required|integer',
    //     ]);
    //     StokModel::create($request->all());
    //     return redirect('/stok')->with('success', 'Data stok berhasil disimpan');
    // }

    public function show($id) {
        $stok = StokModel::with(['supplier', 'barang', 'user'])->find($id);
        $breadcrumb = (object) ['title' => 'Detail Stok', 'list' => ['Home', 'Stok', 'Detail']];
        $page = (object) ['title' => 'Detail stok'];
        $activeMenu = 'stok';
        return view('stok.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'stok' => $stok, 'activeMenu' => $activeMenu]);
    }

    // public function edit($id) {
    //     $stok = StokModel::find($id);
    //     $supplier = SupplierModel::all();
    //     $barang = BarangModel::all();
    //     $user = UserModel::all();
    //     $breadcrumb = (object) ['title' => 'Edit Stok', 'list' => ['Home', 'Stok', 'Edit']];
    //     $page = (object) ['title' => 'Edit stok'];
    //     $activeMenu = 'stok';
    //     return view('stok.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'stok' => $stok, 'supplier' => $supplier, 'barang' => $barang, 'user' => $user, 'activeMenu' => $activeMenu]);
    // }

    // public function update(Request $request, $id) {
    //     $request->validate([
    //         'supplier_id' => 'required|integer',
    //         'barang_id'   => 'required|integer',
    //         'user_id'     => 'required|integer',
    //         'stok_tanggal'=> 'required|date',
    //         'stok_jumlah' => 'required|integer',
    //     ]);
    //     StokModel::find($id)->update($request->all());
    //     return redirect('/stok')->with('success', 'Data stok berhasil diubah');
    // }

    // public function destroy($id) {
    //     StokModel::destroy($id);
    //     return redirect('/stok')->with('success', 'Data stok berhasil dihapus');
    // }

    // public function index() {

    //     // DB::insert('insert into t_stok (supplier_id, barang_id, user_id, stok_tanggal, stok_jumlah, created_at) values(?, ?, ?, ?, ?, ?)', 
    //     //     [1, 1, 1, now(), 50, now()]
    //     // );
    //     // return 'Insert data stok baru berhasil';

    //     // $row = DB::update('update t_stok set stok_jumlah = ? where stok_id = ?', 
    //     //     [55, 16]
    //     // );
    //     // return 'Update data stok berhasil. Jumlah data yg di update: '.$row.' baris';

    //     // $row = DB::delete('delete from t_stok where stok_id = ?', 
    //     //     [16]
    //     // );
    //     // return 'Delete data stok berhasil. Jumlah data yg dihapus: '.$row.' baris';

    //     $data = DB::select('select * from t_stok');
    //     return view('stok', ['data' => $data] );
    // }
=======

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
>>>>>>> 8ff7ed2912a5ba3f923844970145ce6766b169aa
}
