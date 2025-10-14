<?php

namespace App\Policies;

use App\Constants\RoleConstants;
use App\Models\Club;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClubPolicy
{
    /**
     * Determine whether the user can view any clubs.
     */
    public function viewAny(User $user): bool
    {
        return $user->role && in_array($user->role->value, [
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            RoleConstants::SOCCER_REFEREE,
            RoleConstants::FINANCE_DEPARTMENT_HEAD,
            RoleConstants::FINANCE_DEPARTMENT_SPECIALIST,
        ]);
    }

    /**
     * Determine whether the user can view the club.
     */
    public function view(User $user, Club $club): bool
    {
        return $user->role && in_array($user->role->value, [
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            RoleConstants::SOCCER_REFEREE,
            RoleConstants::FINANCE_DEPARTMENT_HEAD,
            RoleConstants::FINANCE_DEPARTMENT_SPECIALIST,
        ]);
    }

    /**
     * Determine whether the user can create clubs.
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
     * Determine whether the user can update the club.
     */
    public function update(User $user, Club $club): bool
    {
        return $user->role && in_array($user->role->value, [
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
        ]);
    }

    /**
     * Determine whether the user can delete the club.
     */
    public function delete(User $user, Club $club): bool
    {
        return $user->role && in_array($user->role->value, [
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
        ]);
    }

    /**
     * Determine whether the user can manage club hierarchy.
     */
    public function manageHierarchy(User $user, Club $club): bool
    {
        return $user->role && in_array($user->role->value, [
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
        ]);
    }
}