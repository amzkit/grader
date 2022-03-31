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
        $problem = Problem::join("languages", "languages.id", "=", "problems.language_id")
            ->select(
                "problems.*",
                "languages.lang as language",
                "languages.type",
            )
            ->get();
        return response()->json(['success' => true, 'payload' =>  $problem]);
    }

    public function delProblem($id)
    {
        if (Problem::where('id', $id)->exists()) {
            Problem::find($id)->delete();
            return response()->json([
                "message" => "records delete successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "not found"
            ], 404);
        }
    }

    public function updateProblem(Request $request)
    {
        $problem = Problem::where("id", $request->id)->first();

        if (isset($request->file) && $request->file != "") {
            if ($problem->file !== $request->file) {
                $originalName = $request->file('file')->getClientOriginalName();
                $path = $request->file('file')->storeAs('problem_file', $originalName);
                $problem->file = $path;
            }
        }

        $problem->update(
            [
                'title' => $request->title,
                'question' => $request->question,
                'score' => $request->score,
                'tolerant' => $request->tolerant,
                'level' => $request->level,
                'language_id' => $request->language_id,
                'file' => $problem->file
            ]
        );
        return response()->json(['success' => true, 'payload' => $problem]);
    }
}
