<?php

namespace App\Providers;

use App\Constants\RoleConstants;
use App\Models\MatchEntity;
use App\Models\User;
use App\Models\Role;
use App\Models\Trip;
use App\Models\Club;
use App\Models\Stadium;
use App\Policies\MatchPolicy;
use App\Policies\TripPolicy;
use App\Policies\ClubPolicy;
use App\Policies\StadiumPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        MatchEntity::class => MatchPolicy::class,
        Trip::class => TripPolicy::class,
        Club::class => ClubPolicy::class,
        Stadium::class => StadiumPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        $this->registerGates();
    }

    /**
     * Регистрация Gates для авторизации на основе ролей
     */
    private function registerGates(): void
    {
        // Административные возможности
        Gate::before(function (User $user, string $ability) {
            if ($user->role && $user->role->value === RoleConstants::ADMINISTRATOR) {
                return true;
            }
        });

        // Управление пользователями
        Gate::define('manage-users', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::FINANCE_DEPARTMENT_HEAD,
            ]);
        });

        Gate::define('create-users', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::FINANCE_DEPARTMENT_HEAD,
            ]);
        });

        // Управление судьями
        Gate::define('manage-referees', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('assign-referees', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        // Финансовые операции
        Gate::define('manage-finance', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::FINANCE_DEPARTMENT_HEAD,
                RoleConstants::FINANCE_DEPARTMENT_SPECIALIST,
                RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT,
            ]);
        });

        Gate::define('approve-payments', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::FINANCE_DEPARTMENT_HEAD,
                RoleConstants::FINANCE_DEPARTMENT_SPECIALIST,
            ]);
        });

        // Логистика
        Gate::define('manage-logistics', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('manage-trips', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        // Управление матчами
        Gate::define('manage-matches', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('view-matches', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT,
                RoleConstants::SOCCER_REFEREE,
                RoleConstants::FINANCE_DEPARTMENT_HEAD,
                RoleConstants::FINANCE_DEPARTMENT_SPECIALIST,
            ]);
        });

        // Просмотр собственных матчей (для судей)
        Gate::define('view-own-matches', function (User $user) {
            return $user->role && $user->role->value === RoleConstants::SOCCER_REFEREE;
        });

        // Управление клубами и стадионами
        Gate::define('manage-clubs', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('manage-stadiums', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        // Просмотр отчетов
        Gate::define('view-reports', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::FINANCE_DEPARTMENT_HEAD,
                RoleConstants::FINANCE_DEPARTMENT_SPECIALIST,
                RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT,
            ]);
        });

        // Управление протоколами
        Gate::define('manage-protocols', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
                RoleConstants::SOCCER_REFEREE,
            ]);
        });

        Gate::define('sign-protocols', function (User $user) {
            return $user->role && $user->role->value === RoleConstants::SOCCER_REFEREE;
        });

        // Системные операции
        Gate::define('access-admin-panel', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::FINANCE_DEPARTMENT_HEAD,
            ]);
        });

        Gate::define('manage-system-settings', function (User $user) {
            return $user->role && $user->role->value === RoleConstants::ADMINISTRATOR;
        });

        // Дополнительные Gates для специфических операций
        Gate::define('edit-own-profile', function (User $user, User $profileUser) {
            return $user->id === $profileUser->id;
        });

        Gate::define('view-department-reports', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::FINANCE_DEPARTMENT_HEAD,
                RoleConstants::ADMINISTRATOR,
            ]);
        });
    }
}
