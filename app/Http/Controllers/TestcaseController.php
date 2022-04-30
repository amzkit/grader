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
}
