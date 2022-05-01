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
        $announces = Announce::where('IsActive', '=', 1)->get();
        return response()->json(['success' => true, 'payload' =>  $announces]);
    }

    public function getAnnounceByAdmin()
    {
        $announces = Announce::get();
        return response()->json(['success' => true, 'payload' =>  $announces]);
    }

    public function postAnnounce(Request $request)
    {
        $announce = new Announce($request->all());

        if (isset($request->file) && $request->file != '') {
            $originalName = $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->storeAs('public/announce', $originalName);
            $announce->picture = $path;
            $announce->IsActive = $request->IsActive == true ? 1 : 0;
        }
        $announce->save();
        return response()->json(['success' => true, 'payload' =>  $announce]);
    }

    public function putAnnounce(Request $request)
    {
        $announce = Announce::find($request->id);

        if (is_null($announce)) {
            return response()->json('Record not found!', 401);
        }

        if ($request->file != "") {

            $replace = str_replace("public", "", $announce->picture);

            $image_path = public_path() . '/storage' . $replace;

            if (File::exists($image_path)) {
                File::delete($image_path);
            }

            $originalName = $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->storeAs('public/announce', $originalName);
            $announce->picture = $path;
        }

        $announce->IsActive = $request->IsActive;
        $announce->save();

        return response()->json(['success' => true, 'payload' =>  $announce]);
    }

    public function deleteAnnounce($id)
    {
        $announce = Announce::find($id);

        if (is_null($announce)) {
            return response()->json('Record not found!', 401);
        }

        $image_path = storage_path() . '/' . $announce->picture;

        if (File::exists($image_path)) {
            File::delete($image_path);
            unlink($image_path);
        }

        Announce::find($id)->delete();

        return response()->json(['success' => true, 'message' =>  "Sucessfully deleted"]);
    }
}
