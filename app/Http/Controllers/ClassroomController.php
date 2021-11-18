<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quizs;


class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return response()->json(['success' => true, 'payload' =>  Quizs::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $manage = Quizs::leftJoin('languages', 'quizs.language_id', '=', 'languages.id')
            ->where('classroom_id', $id)
            ->select(
                'quizs.id',
                'languages.id AS languagesId',
                'languages.languagesName',
                'quizs.classroom_id',
                'quizs.score',
                'quizs.send_end_work',
                'quizs.send_start_work',
                'quizs.subject_file_path',
                'quizs.subject_name',
                'quizs.work_name',
            )
            ->get();
        return response()->json(['success' => true, 'payload' =>  $manage]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
