<?php

namespace App\Http\Controllers;

use App\Models\MappingIndex;
use App\Services\MappingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MappingController extends Controller
{
    protected $mappingService;

    public function __construct(MappingService $mappingService)
    {
        $this->mappingService = $mappingService;
    }

    // Menampilkan halaman utama untuk upload
    public function showUploadForm()
    {
        $mappings = MappingIndex::all();
        return view('upload', compact('mappings'));
    }

    // Menangani proses upload file
    public function handleUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'mapping_code' => 'required|string|exists:mapping_indices,code',
        ]);

        $result = $this->mappingService->processUpload($request->file('file'), $request->mapping_code);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    // Menampilkan halaman untuk mendaftar format baru
    public function showRegisterForm()
    {
        return view('register');
    }

    // Menyimpan data dari form pendaftaran format
    public function storeRegisterForm(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:mapping_indices,code',
            'description' => 'required|string',
            'table_name' => 'required|string',
            'header_row' => 'required|integer|min:1',
            'columns' => 'required|array',
            'columns.*.excel_col' => 'required|string',
            'columns.*.db_col' => 'required|string',
        ]);

        // Menggunakan Transaction agar data konsisten
        DB::transaction(function () use ($request) {
            $mappingIndex = MappingIndex::create([
                'code' => $request->code,
                'description' => $request->description,
                'table_name' => $request->table_name,
                'header_row' => $request->header_row,
            ]);

            foreach ($request->columns as $column) {
                $mappingIndex->columns()->create([
                    'excel_column_index' => $column['excel_col'],
                    'table_column_name' => $column['db_col'],
                ]);
            }
        });

        return redirect()->route('upload.form')->with('success', 'Format mapping baru berhasil disimpan!');
    }
}