<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- Tambahkan ini

class MappingIndex extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'table_name',
        'header_row',
        'division_id',
        'created_by_user_id',
    ];

    public function columns(): HasMany
    {
        return $this->hasMany(MappingColumn::class);
    }

    // FUNGSI BARU UNTUK RELASI KE DIVISI
    public function division(): BelongsTo
    {
        // Asumsi nama model Anda adalah Division.php
        return $this->belongsTo(Division::class);
    }
}