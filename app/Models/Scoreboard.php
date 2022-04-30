<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scoreboard extends Model
{
    use HasFactory;
    protected $fillable = [
        'date_send',
        'score',
        'Lang',
        'schedule_id',
        "problem_id",
        "user_id",
        "course_id",
        "name",
        "username"
    ];
}
