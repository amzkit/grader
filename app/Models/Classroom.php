<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id',
        'course_id',
        'section',
        "year",
        "semester",
        "start_datetime",
        "end_datetime",
        "created_at",
        "updated_at"
    ];
}
