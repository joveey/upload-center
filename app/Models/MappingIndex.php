<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- Pastikan ini di-import

class MappingIndex extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'table_name',
        'division_id',
        'code', // Jika ada
    ];

    /**
     * Mendefinisikan relasi One-to-Many ke MappingColumn.
     */
    public function columns(): HasMany // <-- Tambahkan return type hint
    {
        return $this->hasMany(MappingColumn::class, 'mapping_index_id');
    }

    /**
     * Relasi ke Division
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}