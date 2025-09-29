<?php

namespace App\Services;

use App\Models\MappingIndex;
use App\Models\MappingColumn; // <-- 1. Import MappingColumn
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class MappingService
{
    public function createMapping(array $data)
    {
        return DB::transaction(function () use ($data) {
            
            $indexData = Arr::only($data, ['description', 'table_name', 'division_id']);
            $mappingIndex = MappingIndex::create($indexData);

            // --- BAGIAN YANG DIPERBAIKI ---
            if (isset($data['columns']) && is_array($data['columns'])) {
                $columnsToSave = [];
                foreach ($data['columns'] as $column) {
                    // Buat object Model baru, tapi jangan simpan dulu
                    $columnsToSave[] = new MappingColumn($column); 
                }

                // Simpan semua object kolom sekaligus menggunakan relasi
                if (!empty($columnsToSave)) {
                    $mappingIndex->columns()->saveMany($columnsToSave);
                }
            }
            // --- AKHIR BAGIAN ---

            return $mappingIndex;
        });
    }

    public function updateMapping(MappingIndex $mappingIndex, array $data)
    {
        return DB::transaction(function () use ($mappingIndex, $data) {
            
            $indexData = Arr::only($data, ['description', 'table_name']);
            $mappingIndex->update($indexData);
            
            $mappingIndex->columns()->delete();
            
            if (isset($data['columns']) && is_array($data['columns'])) {
                $columnsToSave = [];
                foreach ($data['columns'] as $column) {
                    $columnsToSave[] = new MappingColumn($column);
                }

                if (!empty($columnsToSave)) {
                    $mappingIndex->columns()->saveMany($columnsToSave);
                }
            }

            return $mappingIndex->fresh('columns');
        });
    }
}