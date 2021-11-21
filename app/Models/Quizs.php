<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quizs extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'classroom_id ',
        'language',
        'score',
        'send_start_work',
        'send_end_work',
        'subject_file_path',
        'subject_name',
        'work_name',
        'created_at',
        'updated_at'
    ];
}
