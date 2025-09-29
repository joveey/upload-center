<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMappingRequest;
use App\Http\Requests\UpdateMappingRequest;
use App\Models\MappingIndex;
use App\Services\MappingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MappingController extends Controller
{
    protected $mappingService;

    public function __construct(MappingService $mappingService)
    {
        $this->mappingService = $mappingService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user();

            if ($user->hasRole('super-admin')) {
                return response()->json(
                    MappingIndex::with('division', 'columns')->latest()->get()
                );
            }

            return response()->json(
                MappingIndex::with('division', 'columns')
                    ->where('division_id', $user->division_id)
                    ->latest()
                    ->get()
            );
        } catch (\Exception $e) {
            Log::error('Error fetching mappings: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch mappings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMappingRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Tambahkan division_id dari user yang login
            $data['division_id'] = Auth::user()->division_id;

            $mapping = $this->mappingService->createMapping($data);

            return response()->json([
                'message' => 'Mapping created successfully',
                'data' => $mapping
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Error creating mapping: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'message' => 'Failed to create mapping',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = Auth::user();
            $mapping = MappingIndex::with('columns', 'division')->findOrFail($id);

            if (!$user->hasRole('super-admin') && $mapping->division_id !== $user->division_id) {
                return response()->json(['message' => 'Forbidden'], 403);
            }

            return response()->json($mapping);
            
        } catch (\Exception $e) {
            Log::error('Error fetching mapping: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch mapping',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMappingRequest $request, string $id)
    {
        try {
            $user = Auth::user();
            $mapping = MappingIndex::findOrFail($id);

            if (!$user->hasRole('super-admin') && $mapping->division_id !== $user->division_id) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
            
            $data = $request->validated();
            $updatedMapping = $this->mappingService->updateMapping($mapping, $data);

            return response()->json([
                'message' => 'Mapping updated successfully',
                'data' => $updatedMapping
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating mapping: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update mapping',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = Auth::user();
            $mapping = MappingIndex::findOrFail($id);

            if (!$user->hasRole('super-admin') && $mapping->division_id !== $user->division_id) {
                return response()->json(['message' => 'Forbidden'], 403);
            }

            $mapping->delete();

            return response()->json([
                'message' => 'Mapping deleted successfully'
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('Error deleting mapping: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to delete mapping',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user permissions
     */
    public function getUserPermissions()
    {
        $user = Auth::user();
        
        return response()->json([
            'can_create_mapping' => $user->can('create mapping'),
            'can_register_format' => $user->can('register format'),
            'can_upload_data' => $user->can('upload data'),
            'is_super_admin' => $user->hasRole('super-admin'),
        ]);
    }
}