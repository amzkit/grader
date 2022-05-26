<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testcase;

class TestcaseController extends Controller
{
    public function insert(Request $request)
    {
        $testcase = new Testcase($request->all());

        if (isset($request->missionId) && $request->missionId != '') {
            $testcase->problem_id = $request->missionId;
        }
        $testcase->input = empty($request->input) ? "" : $request->input;
        $testcase->output = empty($request->output) ? "" : $request->output;
        $testcase->save();
        return response()->json(['success' => true, 'payload' =>  $testcase]);
    }

    public function get($id)
    {
        $testcase = Testcase::where('problem_id', $id)->get();
        return response()->json(['success' => true, 'payload' =>  $testcase]);
    }

    public function delete($id)
    {
        if (Testcase::where('id', $id)->exists()) {
            Testcase::where('id', $id)->delete();
            return response()->json([
                "message" => "records delete successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Student not found"
            ], 404);
        }
    }

    public function update(Request $request)
    {
        $testcase = Testcase::where("id", $request->id)->first();
        $testcase->update(
            [
                'input' => $request->input,
                'output' => $request->output,
            ]
        );
        return response()->json(['success' => true, 'payload' =>  $testcase]);
    }
}
