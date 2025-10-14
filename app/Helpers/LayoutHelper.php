<?php

use App\Constants\RoleConstants;

if (!function_exists('get_user_layout')) {
    /**
     * Get the layout file path based on user's role
     *
     * @return string
     */
    function get_user_layout(): string
    {
        if (!auth()->check()) {
            return 'layouts.app';
        }

        $userRole = auth()->user()->role->value ?? null;

        return match ($userRole) {
            RoleConstants::ADMINISTRATOR => 'layouts.admin',
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE => 'layouts.employee',
            RoleConstants::REFEREEING_DEPARTMENT_HEAD => 'layouts.head',
            RoleConstants::FINANCE_DEPARTMENT_SPECIALIST => 'layouts.financial-specialist',
            RoleConstants::FINANCE_DEPARTMENT_HEAD => 'layouts.financial-head',
            RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN => 'layouts.logistician',
            RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT => 'layouts.accountant',
            RoleConstants::SOCCER_REFEREE => 'layouts.referee',
            default => 'layouts.app',
        };
    }
}

if (!function_exists('get_role_layout_map')) {
    /**
     * Get mapping of roles to their layout files
     *
     * @return array
     */
    function get_role_layout_map(): array
    {
        return [
            RoleConstants::ADMINISTRATOR => 'layouts.admin',
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE => 'layouts.employee',
            RoleConstants::REFEREEING_DEPARTMENT_HEAD => 'layouts.head',
            RoleConstants::FINANCE_DEPARTMENT_SPECIALIST => 'layouts.financial-specialist',
            RoleConstants::FINANCE_DEPARTMENT_HEAD => 'layouts.financial-head',
            RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN => 'layouts.logistician',
            RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT => 'layouts.accountant',
            RoleConstants::SOCCER_REFEREE => 'layouts.referee',
        ];
    }
}

if (!function_exists('get_sidebar_for_role')) {
    /**
     * Get the sidebar partial path based on role
     *
     * @param string|null $role
     * @return string
     */
    function get_sidebar_for_role(?string $role = null): string
    {
        $role = $role ?? (auth()->user()->role->value ?? null);

        return match ($role) {
            RoleConstants::ADMINISTRATOR => 'layouts.partials.sidebars.admin',
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE => 'layouts.partials.sidebars.employee',
            RoleConstants::REFEREEING_DEPARTMENT_HEAD => 'layouts.partials.sidebars.head',
            RoleConstants::FINANCE_DEPARTMENT_SPECIALIST => 'layouts.partials.sidebars.financial-specialist',
            RoleConstants::FINANCE_DEPARTMENT_HEAD => 'layouts.partials.sidebars.financial-head',
            RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN => 'layouts.partials.sidebars.logistician',
            RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT => 'layouts.partials.sidebars.accountant',
            RoleConstants::SOCCER_REFEREE => 'layouts.partials.sidebars.referee',
            default => 'layouts.partials.sidebars.employee',
        };
    }
}
