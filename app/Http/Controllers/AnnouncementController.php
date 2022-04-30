<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\Models\Announce;


class AnnouncementController extends Controller
{
    public function getAnnounce()
    {
        $announces = Announce::get();
        return response()->json(['success' => true, 'payload' =>  $announces]);
    }

    public function postAnnounce(Request $request)
    {
        return response()->json(['success' => true, 'payload' =>  $request]);
    }

    public function putAnnounce(Request $request)
    {
        return response()->json(['success' => true, 'payload' =>  $request]);
    }

    public function deleteAnnounce($id)
    {
        return response()->json(['success' => true, 'payload' =>  $id]);
    }
}
