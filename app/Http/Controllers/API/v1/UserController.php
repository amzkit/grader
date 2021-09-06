<?php

namespace App\Http\Controllers\API\v1;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

use App\Models\User;
//use Imagick;

class UserController extends Controller
{
    public function __construct()
    {
    }

    public function setRole(Request $request)
    {
        //dd($request->all());
        //$role = $request->role;
        //dd($request->user());
        $user = User::find($request->user()->id);
        $user->role = $request->role;
        $user->save();
        return response()->json(['success'=>true, 'user'=>$user]);
    }
}
