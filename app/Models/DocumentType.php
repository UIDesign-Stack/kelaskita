<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $fillable = ['name', 'code', 'requires_semester', 'is_active'];

    protected $casts = [
        'requires_semester' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function documents()
    {
        return $this->hasMany(TeachingDocument::class);
    }
}