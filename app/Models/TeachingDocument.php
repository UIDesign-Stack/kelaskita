<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeachingDocument extends Model
{
    protected $fillable = ['subject_id', 'teacher_id', 'document_type_id', 'title', 'semester', 'file_path'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function fileUrl(): ?string
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }
}