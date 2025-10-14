<?php

use App\Constants\RoleConstants;

if (!function_exists('has_role')) {
    /**
     * Check if the authenticated user has a specific role
     *
     * @param string|array $roles
     * @return bool
     */
    function has_role(string|array $roles): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $userRole = auth()->user()->role->value ?? null;

        if (is_array($roles)) {
            return in_array($userRole, $roles);
        }

        return $userRole === $roles;
    }
}

if (!function_exists('is_admin')) {
    /**
     * Check if the authenticated user is an administrator
     *
     * @return bool
     */
    function is_admin(): bool
    {
        return has_role(RoleConstants::ADMINISTRATOR);
    }
}

if (!function_exists('is_head')) {
    /**
     * Check if the authenticated user is any head/manager
     *
     * @return bool
     */
    function is_head(): bool
    {
        return has_role([
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::FINANCE_DEPARTMENT_HEAD,
        ]);
    }
}

if (!function_exists('is_financial_staff')) {
    /**
     * Check if the authenticated user is financial department staff
     *
     * @return bool
     */
    function is_financial_staff(): bool
    {
        return has_role([
            RoleConstants::FINANCE_DEPARTMENT_SPECIALIST,
            RoleConstants::FINANCE_DEPARTMENT_HEAD,
        ]);
    }
}

if (!function_exists('is_refereeing_staff')) {
    /**
     * Check if the authenticated user is refereeing department staff
     *
     * @return bool
     */
    function is_refereeing_staff(): bool
    {
        return has_role([
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
        ]);
    }
}

if (!function_exists('can_manage_users')) {
    /**
     * Check if the authenticated user can manage users
     *
     * @return bool
     */
    function can_manage_users(): bool
    {
        return has_role([
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
        ]);
    }
}

if (!function_exists('can_manage_finance')) {
    /**
     * Check if the authenticated user can manage finance
     *
     * @return bool
     */
    function can_manage_finance(): bool
    {
        return has_role([
            RoleConstants::ADMINISTRATOR,
            RoleConstants::FINANCE_DEPARTMENT_SPECIALIST,
            RoleConstants::FINANCE_DEPARTMENT_HEAD,
            RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT,
        ]);
    }
}

if (!function_exists('can_approve_payments')) {
    /**
     * Check if the authenticated user can approve payments
     *
     * @return bool
     */
    function can_approve_payments(): bool
    {
        return has_role([
            RoleConstants::ADMINISTRATOR,
            RoleConstants::FINANCE_DEPARTMENT_HEAD,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
        ]);
    }
}

if (!function_exists('can_manage_matches')) {
    /**
     * Check if the authenticated user can manage matches
     *
     * @return bool
     */
    function can_manage_matches(): bool
    {
        return has_role([
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
        ]);
    }
}

if (!function_exists('can_manage_referees')) {
    /**
     * Check if the authenticated user can manage referees
     *
     * @return bool
     */
    function can_manage_referees(): bool
    {
        return has_role([
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
        ]);
    }
}

if (!function_exists('can_manage_logistics')) {
    /**
     * Check if the authenticated user can manage logistics
     *
     * @return bool
     */
    function can_manage_logistics(): bool
    {
        return has_role([
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
        ]);
    }
}

if (!function_exists('can_view_reports')) {
    /**
     * Check if the authenticated user can view reports
     *
     * @return bool
     */
    function can_view_reports(): bool
    {
        return has_role([
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::FINANCE_DEPARTMENT_HEAD,
            RoleConstants::FINANCE_DEPARTMENT_SPECIALIST,
            RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT,
        ]);
    }
}
