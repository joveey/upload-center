<?php

namespace App\Services;

use App\Models\MappingIndex;
use App\Models\MappingColumn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class MappingService
{
    public function createMapping(array $data)
    {
        return DB::transaction(function () use ($data) {
            
            // Ambil data untuk mapping index
            $indexData = Arr::only($data, ['code', 'description', 'table_name', 'header_row', 'division_id']);
            
            // Create mapping index
            $mappingIndex = MappingIndex::create($indexData);

            // Create mapping columns
            if (isset($data['columns']) && is_array($data['columns'])) {
                $columnsToSave = [];
                
                foreach ($data['columns'] as $column) {
                    $columnsToSave[] = new MappingColumn([
                        'excel_column_index' => $column['excel_column_index'],
                        'table_column_name' => $column['table_column_name'],
                        'data_type' => $column['data_type'],
                        'is_required' => $column['is_required'],
                    ]);
                }

                if (!empty($columnsToSave)) {
                    $mappingIndex->columns()->saveMany($columnsToSave);
                }
            }

            // Load relationships dan return
            return $mappingIndex->load(['columns', 'division']);
        });
    }

    public function updateMapping(MappingIndex $mappingIndex, array $data)
    {
        return DB::transaction(function () use ($mappingIndex, $data) {
            
            // Update mapping index
            $indexData = Arr::only($data, ['description', 'table_name', 'header_row']);
            $mappingIndex->update($indexData);
            
            // Delete old columns
            $mappingIndex->columns()->delete();
            
            // Create new columns
            if (isset($data['columns']) && is_array($data['columns'])) {
                $columnsToSave = [];
                
                foreach ($data['columns'] as $column) {
                    $columnsToSave[] = new MappingColumn([
                        'excel_column_index' => $column['excel_column_index'],
                        'table_column_name' => $column['table_column_name'],
                        'data_type' => $column['data_type'],
                        'is_required' => $column['is_required'],
                    ]);
                }

                if (!empty($columnsToSave)) {
                    $mappingIndex->columns()->saveMany($columnsToSave);
                }
            }

            // Refresh dan load relationships
            return $mappingIndex->fresh(['columns', 'division']);
        });
    }
}