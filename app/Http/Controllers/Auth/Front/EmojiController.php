<?php

namespace App\Http\Controllers\Auth\Front;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use App\Models\Emoji;

class EmojiController extends Controller
{
    public function index()
    {
        $emojis = Emoji::all()->map(function ($emoji) {
            $emoji->image_url = asset('storage/emojis/' . $emoji->profile); // Assuming you store emoji images in a storage folder
            return $emoji;
        });
        return response()->json($emojis);
    }

    public function updateEmoji(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'emoji_id' => 'required|integer|exists:emoji,id', // Make sure emoji_id is valid
        ]);
    
        try {
            // Assuming the authenticated user is available
            $user = auth()->user();
    
            // Update the user's selected emoji (assuming a user has a 'selected_emoji_id' field)
            $user->emoji_id = $request->emoji_id;
            $user->save();
    
            // Return success response
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Error updating emoji: ' . $e->getMessage());
    
            // Return error response
            return response()->json(['success' => false, 'message' => 'Failed to update emoji'], 500);
        }
    }
    
}
