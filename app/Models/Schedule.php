<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'course_id',
        'start_date',
        'end_date',
        "problem_id",
        "language_id",
        "IsActive",
        "IsAnalysis",
        "late",
        "created_at",
        "updated_at",
        'score'
    ];
}
