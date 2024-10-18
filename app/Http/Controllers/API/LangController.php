<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LangController extends Controller
{
    public function updateLanguage(Request $request)
    {
        $language = $request->input('language');
        session(['locale' => $language]);

        return response()->json(['success' => true]);
    }
}
