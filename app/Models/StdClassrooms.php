<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StdClassrooms extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'classroom_id ',
        'student_id ',
        'status',
        'created_at',
        'updated_at',
    ];
}
