<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\LocaleController;
use App\Livewire\MatchManagement;
use App\Livewire\RefereeManagement;
use App\Livewire\UserManagement;
use App\Livewire\FinanceManagement;
use App\Livewire\CountryManagement;
use App\Livewire\CityManagement;
use App\Livewire\LeagueManagement;
use App\Livewire\SeasonManagement;
use App\Livewire\ClubManagement;

// Переключение языка
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

// Главная страница
Route::get('/', function () {
    return view('welcome');
})->middleware('guest');

// Маршруты аутентификации
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Защищенные маршруты
Route::middleware(['auth', 'verified'])->group(function () {
    // Debug route
    Route::get('/debug-auth', function () {
        $user = auth()->user();
        return response()->json([
            'user_id' => $user->id,
            'has_role' => $user->role ? true : false,
            'role_value' => $user->role ? $user->role->value : null,
            'is_administrator' => $user->role && $user->role->value === \App\Constants\RoleConstants::ADMINISTRATOR,
            'can_manage_users' => \Illuminate\Support\Facades\Gate::allows('manage-users'),
            'can_create_users' => \Illuminate\Support\Facades\Gate::allows('create-users'),
        ]);
    });

    // Панель управления
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Управление пользователями (только для административных ролей)
    Route::get('/users', function () {
        return view('users');
    })->name('users')
        ->middleware('auth');

    // Управление судьями
    Route::get('/referees', RefereeManagement::class)
        ->name('referees')
        ->middleware('can:manage-referees');

    // Управление матчами
    Route::get('/matches', MatchManagement::class)
        ->name('matches')
        ->middleware('can:view-matches');

    Route::get('/countries', CountryManagement::class)
        ->name('countries')
        ->middleware('can:manage-countries');

    Route::get('/cities', CityManagement::class)
        ->name('cities')
        ->middleware('can:manage-cities');

    Route::get('/leagues', LeagueManagement::class)
        ->name('leagues')
        ->middleware('can:manage-leagues');

    Route::get('/seasons', SeasonManagement::class)
        ->name('seasons')
        ->middleware('can:manage-seasons');

    Route::get('/clubs', ClubManagement::class)
        ->name('clubs')
        ->middleware('can:manage-clubs');

    // Финансовое управление
    Route::get('/finance', FinanceManagement::class)
        ->name('finance')
        ->middleware('can:manage-finance');

    // Административная панель
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Профиль пользователя
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('can:access-admin-panel')->group(function () {
        // Roles CRUD
        Route::resource('roles', \App\Http\Controllers\RoleController::class)->middleware('can:manage-roles');

        // Users CRUD
        Route::resource('users', \App\Http\Controllers\UserController::class)->middleware('can:manage-users');
    });
});
