<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{
    public function updateLanguage(Request $request)
    {
        $request->validate([
            'language' => 'required|string',
        ]);

        $user = Auth::user();
        $language = $request->input('language');
        session(['locale' => $language]);

        // Update the user's language preference
        $user->language = $language;
        $user->save();

        // return response()->json(['success' => true]);
        return redirect()->back();
    }
}
