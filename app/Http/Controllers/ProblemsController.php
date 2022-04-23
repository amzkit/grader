<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Problem;
use App\Models\Schedule;

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


    public function getProblemGuestById($id)
    {
        $schedule = Schedule::where("id", '=', $id)
            ->first();

        $problem = Problem::where("id", '=', $schedule->problem_id)
            ->first();

        $item = [
            'problem_id' => $problem->id,
            'question' => $problem->question,
            'score' => $problem->score,
            'level' => $problem->level,
            'title' => $problem->title,
            'file' => $problem->file,
            'schedule_id' => $schedule->id,
            'start_date' => $schedule->start_date,
            'end_date' => $schedule->start_date,
        ];
        return response()->json(['success' => true, 'payload' =>  $item]);
    }

    public function download($file_name)
    {
        $file = storage_path("app/problem_file/") . $file_name;
        if (file_exists($file)) {
            return response()->download($file);
        } else {
            return response()->json(['message' => 'File not found.'], 404);
        }
    }

    public function getProblem()
    {
        $problem = Problem::select(
            "problems.*",
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
                'file' => $problem->file
            ]
        );
        return response()->json(['success' => true, 'payload' => $problem]);
    }
}
