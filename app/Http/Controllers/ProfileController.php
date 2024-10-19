<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Emoji;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $userAvatar = Emoji::where('id', $user->emoji_id)->first();
        return view('profile.edit', [
            'user' => $user,
            'avatar' => $userAvatar,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update the user's profile avatar.
     */
    public function updateAvatar(Request $request)
{
    $request->validate([
        'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif',
    ]);

    $user = auth()->user();

    if ($request->hasFile('profile')) {
        // Store the new profile image
        $path = $request->file('profile')->store('profiles', 'public');
        $user->profile = Storage::url($path);
        $user->is_profile = true;
    } else {
        // If no file uploaded and checkbox is checked, ensure the user is using the profile image
        $user->is_profile = $request->input('use_profile') ? true : false;
    }

    $user->save();

    return Redirect::route('profile.avatar')->with('status', 'avatar-updated');
}

    /**
     * Update the user's profile status.
     */
    public function updateProfileStatus(Request $request)
    {
        $request->validate([
            'is_profile' => ['required', 'boolean'],
        ]);

        $user = Auth::user();

        $user->is_profile = $request->is_profile;
        $user->save();

        return response()->json(['success' => true, 'message' => __('change_to_use_profile_picture')]);
        // return Redirect::route('profile.avatar')->with(['status', 'scuccess', 'message', __('change_to_use_profile_piture')]);
    }
}
