<?php

namespace App\Http\Controllers;

use App\Models\MappingIndex;
use App\Services\MappingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MappingController extends Controller
{
    protected $mappingService;

    public function __construct(MappingService $mappingService)
    {
        $this->middleware('auth')->except(['getMappings', 'handleUpload']);
        $this->mappingService = $mappingService;
    }

    public function showUploadForm()
    {
        return view('upload');
    }

    public function showRegisterForm()
    {
        return view('register');
    }

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
        
        DB::transaction(function () use ($request) {
            /** @var \App\Models\User $user */
            $user = auth()->user();

            $mappingIndex = MappingIndex::create([
                'code' => $request->code,
                'description' => $request->description,
                'table_name' => $request->table_name,
                'header_row' => $request->header_row,
                'division_id' => $user->division_id,
                'created_by_user_id' => $user->id,
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

    // ===================================================================
    // API METHODS (CALLED BY VUE)
    // ===================================================================

    public function getMappings()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user(); 
        
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        
        $query = MappingIndex::with(['columns', 'division']);

        // LOGIKA BARU: Jika user TIDAK BISA 'view all formats',
        // maka filter berdasarkan divisinya.
        if (!$user->can('view all formats')) {
            $query->where('division_id', $user->division_id);
        }

        return $query->get();
    }

    public function handleUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'mapping_code' => 'required|string|exists:mapping_indices,code',
            'dynamic_mapping' => 'sometimes|array'
        ]);

        $dynamicMapping = $request->input('dynamic_mapping', []);

        $result = $this->mappingService->processUpload(
            $request->file('file'),
            $request->mapping_code,
            $dynamicMapping
        );
        
        if ($result['success']) {
            return response()->json(['message' => $result['message']], 200);
        }

        return response()->json(['message' => $result['message']], 422);
    }
}