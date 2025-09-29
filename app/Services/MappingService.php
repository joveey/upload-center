<?php

namespace App\Services;

use App\Models\MappingIndex;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class MappingService
{
    public function processUpload($file, $mappingCode)
    {
        // 1. Cari aturan mapping berdasarkan kode
        $mappingIndex = MappingIndex::with('columns')->where('code', $mappingCode)->first();

        if (!$mappingIndex) {
            return ['success' => false, 'message' => 'Mapping dengan kode ' . $mappingCode . ' tidak ditemukan.'];
        }

        // 2. Baca file Excel
        // Kita gunakan ToArrayable, header row diabaikan oleh library, kita handle manual.
        $dataRows = Excel::toCollection(null, $file)[0]; // Ambil sheet pertama

        // 3. Siapkan data untuk dimasukkan ke database
        $dataToInsert = [];
        $headerRowIndex = $mappingIndex->header_row - 1;

        foreach ($dataRows as $index => $row) {
            // Lewati baris header
            if ($index < $headerRowIndex) {
                continue;
            }

            $rowData = [];
            // 4. Lakukan mapping sesuai aturan di mapping_columns
            foreach ($mappingIndex->columns as $columnMap) {
                // Konversi 'A', 'B', 'C' menjadi index array 0, 1, 2
                $excelColumnIndex = ord(strtoupper($columnMap->excel_column_index)) - ord('A');

                if (isset($row[$excelColumnIndex])) {
                    $rowData[$columnMap->table_column_name] = $row[$excelColumnIndex];
                }
            }

            if (!empty($rowData)) {
                $dataToInsert[] = $rowData;
            }
        }

        // 5. Simpan data ke tabel tujuan secara dinamis
        if (!empty($dataToInsert)) {
            DB::table($mappingIndex->table_name)->insert($dataToInsert);
            return ['success' => true, 'message' => 'Berhasil mengimpor ' . count($dataToInsert) . ' baris data.'];
        }

        return ['success' => false, 'message' => 'Tidak ada data untuk diimpor.'];
    }
}