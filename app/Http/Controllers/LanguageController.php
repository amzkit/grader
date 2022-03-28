<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;

class LanguageController extends Controller
{

    public function getLanguage()
    {
        $language = Language::get();
        return response()->json(['success' => true, 'payload' =>  $language]);
    }

    public function insert(Request $request)
    {
        $language = new Language($request->all());
        $language->save();
        return response()->json(['success' => true, 'payload' =>  $language]);
    }
}
