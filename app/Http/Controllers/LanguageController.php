<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function updateLanguage(Request $request)
    {
        $language = $request->input('language');
        session(['locale' => $language]);

        // return response()->json(['success' => true]);
        return redirect()->back();
    }
}
