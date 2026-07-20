<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportCardDetail extends Model
{
    protected $fillable = ['report_card_id', 'subject_id', 'final_score', 'predicate', 'teacher_notes'];

    public function reportCard()
    {
        return $this->belongsTo(ReportCard::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}