<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Problem;

class ProblemsController extends Controller
{
    //
    public function insert(Request $request)
    {
        $problem = new Problem($request->all());

        if (isset($request->file) && $request->file != '') {
            $originalName = $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->storeAs('problem_file', $originalName);
            $problem->file = $path;
        }
        $problem->save();
        return response()->json(['success' => true, 'payload' =>  Problem::all()]);
    }

    public function download($file_name)
    {
        $file = storage_path("app/problem_file/") . $file_name;
        if (file_exists($file)) {
            return response()->download($file);
        } else {
            echo ('File not found.');
        }
    }

    public function getProblem()
    {
        $problem = Problem::get();
        return response()->json(['success' => true, 'payload' =>  $problem]);
    }

    public function updateProblem(Request $request)
    {
        $problem = Problem::where("id", $request->input('id'))->first();

        // if (isset($request->file) && $request->file != '') {
        //     $originalName = $request->file('file')->getClientOriginalName();
        //     $path = $request->file('file')->storeAs('problem_file', $originalName);
        //     $problem->file = $path;
        // }

        // $problem->update(
        //     [
        //         'title' => $request->title,
        //         'question' => $request->question,
        //         'score' => $request->score,
        //         'tolerant' => $request->tolerant,
        //         'level' => $request->level,
        //         'language_id' => $request->language_id,
        //         'file' => $problem->file
        //     ]
        // );
        return response()->json(['success' => true, 'payload' => $request]);
    }
}
