<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManageStdClassrooms extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'std_id',
        'firstName',
        'lastName',
        'status_id',
        'classroom_id',
        'created_at',
        'updated_at',
    ];
}
