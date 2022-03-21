<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testcase extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'input',
        'output',
        "problem_id",
        'created_at',
        "updated_at"
    ];
}
