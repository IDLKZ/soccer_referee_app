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
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('approve-referee-team', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
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

        Gate::define('create-stadiums', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
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

        // Управление услугами
        Gate::define('manage-services', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::FINANCE_DEPARTMENT_HEAD,
                RoleConstants::FINANCE_DEPARTMENT_SPECIALIST,
                RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT,
            ]);
        });

        // Управление отелями
        Gate::define('manage-hotels', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('create-hotels', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('delete-hotels', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('manage-hotel-rooms', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('create-hotel-rooms', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('delete-hotel-rooms', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('manage-facilities', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('create-facilities', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('delete-facilities', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('manage-room-facilities', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('create-room-facilities', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('delete-room-facilities', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        // Управление лигами и сезонами
        Gate::define('manage-leagues', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('manage-seasons', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('create-seasons', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('delete-seasons', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('create-leagues', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('delete-leagues', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        // Просмотр аналитики
        Gate::define('view-analytics', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::FINANCE_DEPARTMENT_HEAD,
            ]);
        });

        // Утверждение актов
        Gate::define('approve-acts', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::FINANCE_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            ]);
        });

        // Управление бухгалтерией
        Gate::define('manage-accounting', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT,
                RoleConstants::FINANCE_DEPARTMENT_HEAD,
            ]);
        });

        // Просмотр календаря
        Gate::define('view-calendar', function (User $user) {
            return $user->role !== null; // Все авторизованные пользователи
        });

        // Управление типами судей
        Gate::define('manage-judge-types', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            ]);
        });

        // Управление связями судей и городов
        Gate::define('manage-judge-cities', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        // Просмотр логов
        Gate::define('view-logs', function (User $user) {
            return $user->role && $user->role->value === RoleConstants::ADMINISTRATOR;
        });

        // Управление ролями
        Gate::define('manage-roles', function (User $user) {
            return $user->role && $user->role->value === RoleConstants::ADMINISTRATOR;
        });

        // Просмотр собственных назначений (для судей)
        Gate::define('view-own-assignments', function (User $user) {
            return $user->role && $user->role->value === RoleConstants::SOCCER_REFEREE;
        });

        // Просмотр собственных поездок (для судей)
        Gate::define('view-own-trips', function (User $user) {
            return $user->role && $user->role->value === RoleConstants::SOCCER_REFEREE;
        });

        // Просмотр собственных выплат (для судей)
        Gate::define('view-own-payments', function (User $user) {
            return $user->role && $user->role->value === RoleConstants::SOCCER_REFEREE;
        });

        // Просмотр собственных приглашений (для судей)
        Gate::define('view-own-invitations', function (User $user) {
            return $user->role && $user->role->value === RoleConstants::SOCCER_REFEREE;
        });

        // Просмотр собственных протоколов (для судей)
        Gate::define('view-own-protocols', function (User $user) {
            return $user->role && $user->role->value === RoleConstants::SOCCER_REFEREE;
        });

        // Просмотр собственных АВР (для судей)
        Gate::define('view-own-avr', function (User $user) {
            return $user->role && $user->role->value === RoleConstants::SOCCER_REFEREE;
        });

        // Первичное утверждение протоколов (для сотрудников департамента судейства)
        Gate::define('approve-primary-protocols', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        // Финальное утверждение протоколов (для руководителя департамента судейства)
        Gate::define('approve-control-protocols', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            ]);
        });

        // Управление городами
        Gate::define('manage-cities', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('create-cities', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
            ]);
        });

        Gate::define('delete-cities', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('manage-countries', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('create-countries', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
            ]);
        });

        Gate::define('delete-countries', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        // Управление транспортом
        Gate::define('manage-transport', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            ]);
        });

        // Управление документами командировок
        Gate::define('manage-trip-documents', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT,
            ]);
        });

        // Сверка платежей
        Gate::define('reconcile-payments', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT,
                RoleConstants::FINANCE_DEPARTMENT_HEAD,
            ]);
        });

        // Просмотр производительности судей
        Gate::define('view-referee-performance', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            ]);
        });

        // Согласование матчей
        Gate::define('approve-matches', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            ]);
        });

        // Управление клуб-стадионами
        Gate::define('manage-club-stadiums', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('create-club-stadiums', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('delete-club-stadiums', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        // Управление типами транспорта
        Gate::define('manage-transport-types', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('create-transport-types', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        Gate::define('delete-transport-types', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        // Управление категориями операций
        Gate::define('manage-category-operations', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        // Управление операциями
        Gate::define('manage-operations', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        // Управление требованиями протоколов
        Gate::define('manage-protocol-requirements', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });

        // Управление общими услугами (типы работ АВР)
        Gate::define('manage-common-services', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
                RoleConstants::FINANCE_DEPARTMENT_HEAD,
                RoleConstants::FINANCE_DEPARTMENT_SPECIALIST,
                RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT,
            ]);
        });

        // Управление АВР (Акты выполненных работ)
        Gate::define('avr-processing', function (User $user) {
            return $user->role && in_array($user->role->value, [
                RoleConstants::ADMINISTRATOR,
                RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            ]);
        });
    }
}
