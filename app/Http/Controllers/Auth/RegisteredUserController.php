<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        // Получаем роли, доступные для регистрации
        $roles = Role::where('can_register', true)->where('is_active', true)->get();

        return view('auth.register', compact('roles'));
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'patronomic' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['required', 'exists:roles,id'],
            'sex' => ['required', 'integer', 'min:1', 'max:2'],
            'iin' => ['nullable', 'string', 'max:12', 'unique:users'],
            'birth_date' => ['nullable', 'date', 'before:today'],
        ]);

        // Проверяем, что роль доступна для регистрации
        $role = Role::findOrFail($request->role_id);
        if (!$role->can_register || !$role->is_active) {
            throw ValidationException::withMessages([
                'role_id' => 'Выбранная роль недоступна для регистрации.',
            ]);
        }

        $user = User::create([
            'role_id' => $request->role_id,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'patronomic' => $request->patronomic,
            'phone' => $request->phone,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'sex' => $request->sex,
            'iin' => $request->iin,
            'birth_date' => $request->birth_date,
            'is_active' => true,
            'is_verified' => false,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}