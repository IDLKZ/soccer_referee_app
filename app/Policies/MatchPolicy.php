<?php

namespace App\Policies;

use App\Constants\RoleConstants;
use App\Models\Match;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MatchPolicy
{
    /**
     * Determine whether the user can view any matches.
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
     * Determine whether the user can view the match.
     */
    public function view(User $user, Match $match): bool
    {
        // Администраторы и руководители отделов могут видеть любой матч
        if (in_array($user->role->value, [
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::FINANCE_DEPARTMENT_HEAD,
        ])) {
            return true;
        }

        // Сотрудники судейского отдела могут видеть любые матчи
        if (in_array($user->role->value, [
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
            RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT,
        ])) {
            return true;
        }

        // Финансовые специалисты могут видеть матчи
        if (in_array($user->role->value, [
            RoleConstants::FINANCE_DEPARTMENT_SPECIALIST,
        ])) {
            return true;
        }

        // Судьи могут видеть только матчи, где они назначены
        if ($user->role->value === RoleConstants::SOCCER_REFEREE) {
            return $match->matchJudges()->where('judge_id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create matches.
     */
    public function create(User $user): bool
    {
        return $user->role && in_array($user->role->value, [
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
        ]);
    }

    /**
     * Determine whether the user can update the match.
     */
    public function update(User $user, Match $match): bool
    {
        return $user->role && in_array($user->role->value, [
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
        ]);
    }

    /**
     * Determine whether the user can delete the match.
     */
    public function delete(User $user, Match $match): bool
    {
        return $user->role && in_array($user->role->value, [
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
        ]);
    }

    /**
     * Determine whether the user can assign referees to the match.
     */
    public function assignReferees(User $user, Match $match): bool
    {
        return $user->role && in_array($user->role->value, [
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
        ]);
    }

    /**
     * Determine whether the user can manage match logistics.
     */
    public function manageLogistics(User $user, Match $match): bool
    {
        return $user->role && in_array($user->role->value, [
            RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
        ]);
    }

    /**
     * Determine whether the user can create protocols for the match.
     */
    public function createProtocol(User $user, Match $match): bool
    {
        // Судьи могут создавать протоколы только для матчей, где они назначены
        if ($user->role->value === RoleConstants::SOCCER_REFEREE) {
            return $match->matchJudges()->where('judge_id', $user->id)->exists();
        }

        // Сотрудники судейского отдела могут создавать протоколы
        return in_array($user->role->value, [
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
        ]);
    }

    /**
     * Determine whether the user can approve match operations.
     */
    public function approveOperations(User $user, Match $match): bool
    {
        return $user->role && in_array($user->role->value, [
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::ADMINISTRATOR,
        ]);
    }
}