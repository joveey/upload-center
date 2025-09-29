<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMappingRequest; // <-- 1. Pastikan import ini ada
use App\Http\Requests\UpdateMappingRequest; // <-- 2. Pastikan import ini ada (untuk update)
use App\Models\MappingIndex;
use App\Services\MappingService;
use Illuminate\Support\Facades\Auth;

class MappingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('super-admin')) {
            return MappingIndex::with('division')->latest()->get();
        }

        return MappingIndex::with('division')
            ->where('division_id', $user->division_id)
            ->latest()
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    // 3. Kembalikan tipe request ke StoreMappingRequest
    public function store(StoreMappingRequest $request) 
    {
        // Gunakan validated() untuk mendapatkan data yang sudah lolos validasi
        $data = $request->validated(); 
        
        // Tambahkan division_id dari user yang login
        $data['division_id'] = Auth::user()->division_id;

        $mapping = (new MappingService())->createMapping($data);

        return response()->json($mapping, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $mapping = MappingIndex::with('columns', 'division')->findOrFail($id);

        if (!$user->hasRole('super-admin') && $mapping->division_id !== $user->division_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $mapping;
    }

    /**
     * Update the specified resource in storage.
     */
    // 4. Perbaiki juga tipe request di fungsi update jika ada
    public function update(UpdateMappingRequest $request, string $id)
    {
        $user = Auth::user();
        $mapping = MappingIndex::findOrFail($id);

        if (!$user->hasRole('super-admin') && $mapping->division_id !== $user->division_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        
        // Gunakan validated() juga di sini
        $data = $request->validated();
        $updatedMapping = (new MappingService())->updateMapping($mapping, $data);

        return response()->json($updatedMapping);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $mapping = MappingIndex::findOrFail($id);

        if (!$user->hasRole('super-admin') && $mapping->division_id !== $user->division_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $mapping->delete();

        return response()->json(null, 204);
    }
}