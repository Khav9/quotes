<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Quote;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all quotes that are younger than 24 hours and not soft-deleted
        $quotes = Quote::where('created_at', '>=', now()->subDay())
            ->whereNull('deleted_at')
            ->get();
    
        return response()->json([
            'quotes' => $quotes,
            'status' => 'success'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'text' => 'required|string',
        ]);

        $lastQuote = Quote::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();

        if ($lastQuote && $lastQuote->created_at->gt(now()->subDay())) {
            return response()->json([
                'message'  =>  __('you_can_only_create_new_quote_once_every_24_hours'),
                'status' => 'error'
            ], 400);
        }

        Quote::create([
            'text' => $request->input('text'),
            'credit_to' => $request->input('credit_to'),
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message'  =>  __('create_quote_successfully'),
            'status' => 'success'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'text' => 'required|string',
            'credit_to' => 'nullable|string|max:255',
        ]);

        $quote = Quote::where('user_id', auth()->id())
            ->where('created_at', '>=', now()->subDay())
            ->orderBy('created_at', 'desc')
            ->first();
        if (!$quote){
            return response()->json([
                'message' => 'Quote not found or older than 24 hours!',
                'status' => 'error'
            ], 404);
        }

        $quote->text = $validatedData['text'];
        $quote->credit_to = $validatedData['credit_to'];
        $quote->save();

        return response()->json([
            'message'  =>   __('quote_update_successfully'),
            'status' => 'success'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        $quote = Quote::where('user_id', auth()->id())
            ->where('created_at', '>=', now()->subDay())
            ->orderBy('created_at', 'desc')
            ->first();
    
        if (!$quote) {
            return response()->json([
                'message' => 'Quote not found or older than 24 hours!',
                'status' => 'error'
            ], 404);
        }
    
        $quote->delete();
        return response()->json([
            'message' => __('quote_deleted_successfully'),
            'status' => 'success'
        ], 200);
    }
}
