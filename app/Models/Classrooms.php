<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classrooms extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'className',
        'created_at',
        'updated_at'
    ];
}
