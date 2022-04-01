<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'created_at',
        'updated_at',
        'problem_id',
        'schedule_id',
        'user_id',
        'code',
        'graded',
        'score',
        'message',
        'IP',
        'output',
        'Lang',
        'fname',
        'compiler_message'
    ];
}
