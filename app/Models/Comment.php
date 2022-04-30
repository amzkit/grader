<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'submission_id',
        'user_comment_id',
        'comment',
        "created_at",
        "updated_at"
    ];
}
