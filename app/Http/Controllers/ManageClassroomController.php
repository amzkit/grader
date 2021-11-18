<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManageClassrooms;

class ManageClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $manage = ManageStdClassrooms::leftJoin(
        //     'manage_classrooms',
        //     'manage_std_classrooms.classroom_id',
        //     '=',
        //     'manage_classrooms.id'
        // )
        //     ->where('std_id', '6104101310')
        //     ->select()
        //     ->get();
        // return response()->json([$manage]);
        return response()->json(['success' => true, 'payload' =>  ManageClassrooms::all()]);
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
        $classroom = new ManageClassrooms($request->all());
        $classroom->save();
        $classroom->id;
        return response()->json(array('success' => true, 'last_insert_id' => $classroom->id, 'payload' => $classroom));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $manage = ManageClassrooms::find($id);
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
