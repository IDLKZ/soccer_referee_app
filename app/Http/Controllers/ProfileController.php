<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function index()
    {
        $user = auth()->user()->load('role');
        return view('profile.index', compact('user'));
    }

    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        $user = auth()->user()->load('role');
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'patronomic' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'address_ru' => 'nullable|string|max:500',
            'address_kk' => 'nullable|string|max:500',
            'address_en' => 'nullable|string|max:500',
        ]);

        $user->update($validated);

        return redirect()->route('profile')
            ->with('success', 'Профиль успешно обновлен');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile')
            ->with('success', 'Пароль успешно изменен');
    }

    /**
     * Update the user's avatar.
     */
    public function updateAvatar(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
        ]);

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->image_url) {
                // You might want to delete old file here
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->update(['image_url' => $path]);
        }

        return redirect()->route('profile')
            ->with('success', 'Аватар успешно обновлен');
    }
}