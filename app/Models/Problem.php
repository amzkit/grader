<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'title',
        'question',
        'level',
        'score',
        "language_id",
        "file",
        "tolerant"
    ];
}
