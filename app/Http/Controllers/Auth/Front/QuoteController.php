<?php

namespace App\Http\Controllers\Auth\Front;

use App\Http\Controllers\Controller;
use App\Models\Emoji;
use Auth;
use Illuminate\Http\Request;
use App\Models\Quote;
use App\Models\User;

class QuoteController extends Controller
{
    // public function index()
    // {
    //     $user = Auth::user();
    //     $quotes = Quote::where('created_at', '>=', now()->subDay())->get();
    //     $quotes = $quotes->filter(function ($quote) use ($user) {
    //         return $quote->user_id !== $user->id;
    //     });
    //     $userQuote = Quote::where('user_id', $user->id)->where('created_at', '>=', now()->subDay())->first();
    //     if ($userQuote) {
    //         $quotes->prepend($userQuote);
    //     }
    //     $avatar = Emoji::where('id', $user->emoji_id)->first();
    //     $quotes = $quotes->map(function ($quote) use ($avatar) {
    //         $quote->avatar = $avatar;
    //         return $quote;
    //     });
    //     return view('dashboard', [
    //         'quotes' => $quotes
    //     ]);
    // }

public function index()
{
    $user = Auth::user();

    // Retrieve all quotes created in the last 24 hours
    $quotes = Quote::where('created_at', '>=', now()->subDay())->get();

    // Filter out the current user's quotes
    $quotes = $quotes->filter(function ($quote) use ($user) {
        return $quote->user_id !== $user->id;
    });

    // Retrieve the current user's quote if it exists
    $userQuote = Quote::where('user_id', $user->id)->where('created_at', '>=', now()->subDay())->first();
    if ($userQuote) {
        $quotes->prepend($userQuote);
    }

    // Assign the correct avatar to each quote
    $quotes = $quotes->map(function ($quote)  {
        $userId = $quote->user_id;
        $avatarId = User::where('id', $userId)->first()->emoji_id;
        $avatar = Emoji::where('id', $avatarId)->first();
        $quote->avatar = $avatar;

        return $quote;
    });

    return view('dashboard', [
        'quotes' => $quotes
    ]);
}
    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'text' => 'required|string',
        ]);

        $lastQuote = Quote::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();

        if ($lastQuote && $lastQuote->created_at->gt(now()->subDay())) {
            return redirect()->route('dashboard')->with('error', __('you_can_only_create_new_quote_once_every_24_hours'));
        }

        Quote::create([
            'text' => $request->input('text'),
            'credit_to' => $request->input('credit_to'),
            'user_id' => $user->id,
        ]);

        return redirect()->route('dashboard')->with('success', __('create_quote_successfully'));
    }

    public function update(Request $request, $id)
    {
        $quote = Quote::findOrFail($id);
        $quote->text = $request->input('text');
        $quote->credit_to = $request->input('credit_to');
        $quote->save();
        return redirect()->back()->with('success', __('quote_update_successfully'));
    }
    
    public function destroy($id)
    {
        $quote = Quote::findOrFail($id);
    
        if (auth()->id() !== $quote->user_id) {
            return redirect()->back()->with('error', 'You are not authorized to delete this testimonial.');
        }
    
        $quote->delete();
        return redirect()->back()->with('success', __('qoute_deleted_successfully'));
    }
    
}
