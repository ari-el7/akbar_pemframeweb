<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SupplierModel;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory; // Import Excel
use Barryvdh\DomPDF\Facade\Pdf;         // Import PDF

class SupplierController extends Controller
{

    public function index()
    {
        $breadcrumb = (object) ['title' => 'Daftar Supplier', 'list' => ['Home', 'Supplier']];
        $page = (object) ['title' => 'Daftar supplier barang'];
        $activeMenu = 'supplier';
        return view('supplier.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $supplier = SupplierModel::select('supplier_id', 'supplier_kode', 'supplier_nama', 'supplier_alamat');
        return DataTables::of($supplier)
            ->addIndexColumn()
            ->addColumn('aksi', function ($supplier) {
                $btn = '<a href="'.url('/supplier/' . $supplier->supplier_id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn  .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show($id)
{
    $supplier = SupplierModel::find($id);

    $breadcrumb = (object) [
        'title' => 'Detail Supplier',
        'list'  => ['Home', 'Supplier', 'Detail']
    ];

    $page = (object) [
        'title' => 'Detail Supplier'
    ];

    $activeMenu = 'supplier'; // <--- Tambahkan variabel ini

    return view('supplier.show', [
        'breadcrumb' => $breadcrumb, 
        'page' => $page, 
        'supplier' => $supplier,
        'activeMenu' => $activeMenu // <--- Pastikan dikirim ke view
    ]);
}
    // --- FITUR IMPORT EXCEL ---
    public function import()
    {
        return view('supplier.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_supplier' => ['required', 'mimes:xlsx,xls', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_supplier');
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
                            'supplier_kode'   => $value['A'],
                            'supplier_nama'   => $value['B'],
                            'supplier_alamat' => $value['C'],
                            'created_at'      => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    SupplierModel::insertOrIgnore($insert);
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
        $supplier = SupplierModel::select('supplier_kode', 'supplier_nama', 'supplier_alamat')
            ->orderBy('supplier_kode')
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Supplier');
        $sheet->setCellValue('C1', 'Nama Supplier');
        $sheet->setCellValue('D1', 'Alamat Supplier');

        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;
        foreach ($supplier as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->supplier_kode);
            $sheet->setCellValue('C' . $baris, $value->supplier_nama);
            $sheet->setCellValue('D' . $baris, $value->supplier_alamat);
            $baris++;
            $no++;
        }

        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Supplier');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Supplier ' . date('Y-m-d H:i:s') . '.xlsx';

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
        $supplier = SupplierModel::select('supplier_kode', 'supplier_nama', 'supplier_alamat')
            ->orderBy('supplier_kode')
            ->get();

        $pdf = Pdf::loadView('supplier.export_pdf', ['supplier' => $supplier]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", false);
        $pdf->render();

        return $pdf->stream('Data Supplier ' . date('Y-m-d H:i:s') . '.pdf');
    }
    
    // ... (method create_ajax, store_ajax, edit_ajax, update_ajax, confirm_ajax, delete_ajax biarkan saja) ...
    public function create_ajax() { return view('supplier.create_ajax'); }
    public function store_ajax(Request $request) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = ['supplier_kode' => 'required|min:2|unique:m_supplier,supplier_kode', 'supplier_nama' => 'required|max:100', 'supplier_alamat' => 'required|max:255'];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) return response()->json(['status' => false, 'message' => 'Validasi Gagal', 'msgField' => $validator->errors()]);
            SupplierModel::create($request->all());
            return response()->json(['status' => true, 'message' => 'Data berhasil disimpan']);
        }
        return redirect('/');
    }
    public function edit_ajax($id) { $supplier = SupplierModel::find($id); return view('supplier.edit_ajax', ['supplier' => $supplier]); }
    public function update_ajax(Request $request, $id) {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = ['supplier_kode' => 'required|min:2|unique:m_supplier,supplier_kode,'.$id.',supplier_id', 'supplier_nama' => 'required|max:100', 'supplier_alamat' => 'required|max:255'];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) return response()->json(['status' => false, 'message' => 'Validasi Gagal', 'msgField' => $validator->errors()]);
            $check = SupplierModel::find($id);
            if ($check) { $check->update($request->all()); return response()->json(['status' => true, 'message' => 'Data berhasil diupdate']); }
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
        }
        return redirect('/');
    }
    public function confirm_ajax($id) { $supplier = SupplierModel::find($id); return view('supplier.confirm_ajax', ['supplier' => $supplier]); }
    public function delete_ajax(Request $request, $id) {
        if ($request->ajax() || $request->wantsJson()) {
            $supplier = SupplierModel::find($id);
            if ($supplier) { $supplier->delete(); return response()->json(['status' => true, 'message' => 'Data berhasil dihapus']); }
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
        }
        return redirect('/');
    }
}
//      public function index() {
//         // DB::insert('insert into m_supplier (supplier_kode, supplier_nama, supplier_alamat, created_at) values(?, ?, ?, ?)', 
//         //     ['SUP04', 'PT. Maju Berkah', 'Jl. Merdeka No. 10', now()]
//         // );
//         // return 'Insert data supplier baru berhasil';

//         // $row = DB::update('update m_supplier set supplier_alamat = ? where supplier_kode = ?', 
//         //     ['Jl. Sudirman No. 5', 'SUP04']
//         // );
//         // return 'Update data supplier berhasil. Jumlah data yg di update: '.$row.' baris';

//         // $row = DB::delete('delete from m_supplier where supplier_kode = ?', 
//         //     ['SUP04']
//         // );
//         // return 'Delete data supplier berhasil. Jumlah data yg dihapus: '.$row.' baris';

//         $data = DB::select('select * from m_supplier');
//         return view('supplier', ['data' => $data] );
//     }
// }
