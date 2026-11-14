<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    protected $fillable = [
        'exam_name',
        'subject',
        'topic',
        'questions',
    ];

    protected function casts(): array{
        return[
            'questions'=>'array',
        ];
    }
}
