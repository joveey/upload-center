<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MappingIndex extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'table_name',
        'header_row',
        'division_id',
    ];

    protected $casts = [
        'header_row' => 'integer',
    ];

    public function columns(): HasMany
    {
        return $this->hasMany(MappingColumn::class, 'mapping_index_id');
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }
}