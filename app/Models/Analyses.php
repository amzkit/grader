<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analyses extends Model
{
    use HasFactory;
    protected $fillable = [
        "id",
        "submission_id",
        "testcase_id",
        "message",
        "output",
        "created_at", 
        "updated_at"
    ];
}
