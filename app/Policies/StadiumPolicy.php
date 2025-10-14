<?php

namespace App\Policies;

use App\Constants\RoleConstants;
use App\Models\Stadium;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StadiumPolicy
{
    /**
     * Determine whether the user can view any stadiums.
     */
    public function viewAny(User $user): bool
    {
        return $user->role && in_array($user->role->value, [
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            RoleConstants::SOCCER_REFEREE,
            RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
            RoleConstants::FINANCE_DEPARTMENT_HEAD,
            RoleConstants::FINANCE_DEPARTMENT_SPECIALIST,
        ]);
    }

    /**
     * Determine whether the user can view the stadium.
     */
    public function view(User $user, Stadium $stadium): bool
    {
        return $user->role && in_array($user->role->value, [
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            RoleConstants::SOCCER_REFEREE,
            RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
            RoleConstants::FINANCE_DEPARTMENT_HEAD,
            RoleConstants::FINANCE_DEPARTMENT_SPECIALIST,
        ]);
    }

    /**
     * Determine whether the user can create stadiums.
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
     * Determine whether the user can update the stadium.
     */
    public function update(User $user, Stadium $stadium): bool
    {
        return $user->role && in_array($user->role->value, [
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
        ]);
    }

    /**
     * Determine whether the user can delete the stadium.
     */
    public function delete(User $user, Stadium $stadium): bool
    {
        return $user->role && in_array($user->role->value, [
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
        ]);
    }

    /**
     * Determine whether the user can manage stadium capacity.
     */
    public function manageCapacity(User $user, Stadium $stadium): bool
    {
        return $user->role && in_array($user->role->value, [
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
        ]);
    }
}