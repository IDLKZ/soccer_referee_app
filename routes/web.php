<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProfileController;
use App\Livewire\MatchManagement;
use App\Livewire\RefereeManagement;
use App\Livewire\UserManagement;
use App\Livewire\FinanceManagement;
use App\Livewire\CountryManagement;
use App\Livewire\CityManagement;
use App\Livewire\LeagueManagement;
use App\Livewire\SeasonManagement;
use App\Livewire\ClubManagement;
use App\Livewire\StadiumManagement;
use App\Livewire\ClubStadiumManagement;
use App\Livewire\HotelManagement;
use App\Livewire\HotelRoomManagement;
use App\Livewire\TransportTypeManagement;
use App\Livewire\CategoryOperationManagement;
use App\Livewire\OperationManagement;
use App\Livewire\FacilityManagement;
use App\Livewire\RoomFacilityManagement;
use App\Livewire\JudgeTypeManagement;
use App\Livewire\CommonServiceManagement;
use App\Livewire\ProtocolRequirementManagement;
use App\Livewire\JudgeCityManagement;
use App\Livewire\MyInvitationToBeReferee;
use App\Livewire\MatchEntityManagement;
use App\Livewire\MatchEntityView;
use App\Livewire\MatchAssignmentCards;
use App\Livewire\MatchAssignmentDetail;

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

    Route::get('/stadiums', StadiumManagement::class)
        ->name('stadiums')
        ->middleware('can:manage-stadiums')
    ;

    Route::get('/club-stadiums', ClubStadiumManagement::class)
        ->name('club-stadiums')
        ->middleware('can:manage-club-stadiums');

    Route::get('/hotels', HotelManagement::class)
        ->name('hotels')
        ->middleware('can:manage-hotels');

    Route::get('/hotel-rooms', HotelRoomManagement::class)
        ->name('hotel-rooms')
        ->middleware('can:manage-hotel-rooms');

    Route::get('/transport-types', TransportTypeManagement::class)
        ->name('transport-types')
        ->middleware('can:manage-transport-types');

    Route::get('/category-operations', CategoryOperationManagement::class)
        ->name('category-operations')
        ->middleware('can:manage-category-operations');

    Route::get('/operations', OperationManagement::class)
        ->name('operations')
        ->middleware('can:manage-operations');

    Route::get('/facilities', FacilityManagement::class)
        ->name('facilities')
        ->middleware('can:manage-facilities');

    Route::get('/room-facilities', RoomFacilityManagement::class)
        ->name('room-facilities')
        ->middleware('can:manage-room-facilities');

    Route::get('/judge-types', JudgeTypeManagement::class)
        ->name('judge-types')
        ->middleware('can:manage-judge-types');

    Route::get('/common-services', CommonServiceManagement::class)
        ->name('common-services')
        ->middleware('can:manage-common-services');

    Route::get('/protocol-requirements', ProtocolRequirementManagement::class)
        ->name('protocol-requirements')
        ->middleware('can:manage-protocol-requirements');

    Route::get('/judge-cities', JudgeCityManagement::class)
        ->name('judge-cities')
        ->middleware('can:manage-judge-cities');

    Route::get('/match-entity-management', MatchEntityManagement::class)
        ->name('match-entity-management')
        ->middleware('can:manage-matches');

    Route::get('/match-entity/{id}', MatchEntityView::class)
        ->name('match-entity-view')
        ->middleware('can:view-matches');

    // Назначение судей на матч
    Route::get('/match-assignment-cards', MatchAssignmentCards::class)
        ->name('match-assignment-cards')
        ->middleware('can:assign-referees');

    Route::get('/match-assignment-detail/{id}', MatchAssignmentDetail::class)
        ->name('match-assignment-detail')
        ->middleware('can:assign-referees');

    // Referee routes
    Route::get('/referee/my-invitations', MyInvitationToBeReferee::class)
        ->name('referee.my-invitations')
        ->middleware('can:view-own-invitations');

    Route::get('/referee/my-business-trips', \App\Livewire\MyBusinessTrips::class)
        ->name('referee.my-business-trips')
        ->middleware('can:view-own-trips');

    // Referee team approval (Head of Refereeing Department)
    Route::get('/referee-team-approval-cards', \App\Livewire\RefereeTeamApprovalCards::class)
        ->name('referee-team-approval-cards')
        ->middleware('can:approve-referee-team');

    Route::get('/referee-team-approval-detail/{id}', \App\Livewire\RefereeTeamApprovalDetail::class)
        ->name('referee-team-approval-detail')
        ->middleware('can:approve-referee-team');

    // Business Trip Management (Logistician)
    Route::get('/business-trip-cards', \App\Livewire\BusinessTripCards::class)
        ->name('business-trip-cards')
        ->middleware('can:manage-logistics');

    Route::get('/business-trip-detail/{id}', \App\Livewire\BusinessTripDetail::class)
        ->name('business-trip-detail')
        ->middleware('can:manage-logistics');

    // Финансовое управление
    Route::get('/finance', FinanceManagement::class)
        ->name('finance')
        ->middleware('can:manage-finance');

    // Административная панель
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Профиль пользователя
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::put('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('can:access-admin-panel')->group(function () {
        // Roles CRUD
        Route::resource('roles', \App\Http\Controllers\RoleController::class)->middleware('can:manage-roles');

        // Users CRUD
        Route::resource('users', \App\Http\Controllers\UserController::class)->middleware('can:manage-users');
    });
});
