<?php

namespace App\Policies;

use App\Constants\RoleConstants;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TripPolicy
{
    /**
     * Determine whether the user can view any trips.
     */
    public function viewAny(User $user): bool
    {
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
    }

    /**
     * Determine whether the user can view the trip.
     */
    public function view(User $user, Trip $trip): bool
    {
        // Администраторы и руководители могут видеть любые поездки
        if (in_array($user->role->value, [
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::FINANCE_DEPARTMENT_HEAD,
        ])) {
            return true;
        }

        // Сотрудники судейского отдела могут видеть любые поездки
        if (in_array($user->role->value, [
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
            RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT,
        ])) {
            return true;
        }

        // Финансовые специалисты могут видеть поездки
        if ($user->role->value === RoleConstants::FINANCE_DEPARTMENT_SPECIALIST) {
            return true;
        }

        // Судьи могут видеть только свои поездки
        if ($user->role->value === RoleConstants::SOCCER_REFEREE) {
            return $trip->matchJudges()->where('judge_id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create trips.
     */
    public function create(User $user): bool
    {
        return $user->role && in_array($user->role->value, [
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
        ]);
    }

    /**
     * Determine whether the user can update the trip.
     */
    public function update(User $user, Trip $trip): bool
    {
        return $user->role && in_array($user->role->value, [
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
        ]);
    }

    /**
     * Determine whether the user can delete the trip.
     */
    public function delete(User $user, Trip $trip): bool
    {
        return $user->role && in_array($user->role->value, [
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
        ]);
    }

    /**
     * Determine whether the user can manage trip logistics.
     */
    public function manageLogistics(User $user, Trip $trip): bool
    {
        return $user->role && in_array($user->role->value, [
            RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
        ]);
    }

    /**
     * Determine whether the user can approve trip expenses.
     */
    public function approveExpenses(User $user, Trip $trip): bool
    {
        return $user->role && in_array($user->role->value, [
            RoleConstants::FINANCE_DEPARTMENT_HEAD,
            RoleConstants::FINANCE_DEPARTMENT_SPECIALIST,
            RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT,
        ]);
    }

    /**
     * Determine whether the user can manage trip documents.
     */
    public function manageDocuments(User $user, Trip $trip): bool
    {
        return $user->role && in_array($user->role->value, [
            RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
        ]);
    }

    /**
     * Determine whether the user can create work acts for the trip.
     */
    public function createWorkAct(User $user, Trip $trip): bool
    {
        // Судьи могут создавать акты только для своих поездок
        if ($user->role->value === RoleConstants::SOCCER_REFEREE) {
            return $trip->matchJudges()->where('judge_id', $user->id)->exists();
        }

        // Сотрудники судейского отдела и бухгалтерия могут создавать акты
        return in_array($user->role->value, [
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT,
        ]);
    }
}