<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManageStdClassrooms;

class ManageStdClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['success' => true, 'payload' =>  ManageStdClassrooms::all()]);
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
        $std_classroom = new ManageStdClassrooms($request->all());
        $std_classroom->save();
        $std_classroom->id;
        return response()->json(['success' => true, 'payload' =>  $request->all()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $manage = ManageStdClassrooms::leftJoin('statuses', 'manage_std_classrooms.status_id', '=', 'statuses.id')->where('classroom_id', $id)
            ->select(
                'manage_std_classrooms.id',
                'manage_std_classrooms.std_id',
                'manage_std_classrooms.firstName',
                'manage_std_classrooms.lastName',
                'statuses.statusName',
                'manage_std_classrooms.classroom_id'
            )

            ->get();
        return response()->json(['success' => true, 'payload' =>  $manage->all()]);
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
        $manage = ManageStdClassrooms::find($id);
        $manage->std_id = $request->input('std_id');
        $manage->firstName = $request->input('firstName');
        $manage->firstName = $request->input('firstName');
        $manage->lastName = $request->input('lastName');
        $manage->status_id = $request->input('status_id');
        $manage->save();
        return response()->json(['success' => true, 'payload' =>  $manage->all()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $manage = ManageStdClassrooms::find($id);
        $manage->delete();
        return response()->json(['success' => true, 'payload' =>  $manage->all()]);
    }
}
