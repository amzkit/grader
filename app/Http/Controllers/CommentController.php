<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function insertComment(Request $request)
    {
        if (auth()->user()->role_ta == 1) {
            $comment = new Comment;
            $comment->submission_id = $request->submission_id;
            $comment->user_comment_id = $request->user_id;
            $comment->comment = $request->comment;
            $comment->save();
            return response()->json(['success' => true, 'payload' =>  $comment]);
        }
        return response()->json(['message' =>  "Your don't Teacher Assistant"]);;
    }
}
