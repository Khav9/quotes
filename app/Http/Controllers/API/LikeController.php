<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Auth;
use App\Models\Fav;
use App\Models\Quote;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * like or unlike a quote
     */
    public function toggleLike($quoteId)
    {
        $userId = Auth::id();

        $existingFav = Fav::where('user_id', $userId)
                          ->where('quote_id', $quoteId)
                          ->first();

        if ($existingFav) {
            $existingFav->delete();
            $quote = Quote::where('id', $quoteId)->first();

            if ($quote->favs > 0) {
                Quote::where('id', $quoteId)->decrement('favs');
            }
            
            $existingFav->delete();
            return response()->json([
                'message'  =>  __('quote_unliked_successfully'),
                'status' => 'success'
            ], 200);

        } else {
            Fav::create([
                'user_id' => $userId,
                'quote_id' => $quoteId
            ]);
            Quote::where('id', $quoteId)->increment('favs');
            
            return response()->json([
                'message'  =>  __('quote_like_successfully'),
                'status' => 'success'
            ], 201);
        }
    }
}
