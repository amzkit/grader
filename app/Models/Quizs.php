<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quizs extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'classroom_id',
        'work_name',
        'subject_name',
        'subject_file_path',
        'score',
        'language_id',
        'send_start_work',
        'send_end_work',
        'created_at',
        'updated_at'
    ];
}
