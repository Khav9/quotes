<?php

namespace App\Http\Controllers\Auth\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Fav;
use App\Models\Quote;

class FavController extends Controller
{
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
            return redirect()->back()->with('success', __('quote_unliked_successfully'));

        } else {
            Fav::create([
                'user_id' => $userId,
                'quote_id' => $quoteId
            ]);
            Quote::where('id', $quoteId)->increment('favs');

            return redirect()->back()->with('success', __('quote_like_successfully'));
        }
    }
}