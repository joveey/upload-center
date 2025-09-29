<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MappingIndex extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'table_name',
        'header_row',
    ];

    public function columns(): HasMany
    {
        return $this->hasMany(MappingColumn::class);
    }
}