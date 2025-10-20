<?php

if (!function_exists('pluralize_ru')) {
    /**
     * Правильное склонение русских слов по числительным
     *
     * @param int $count Количество
     * @param array $forms Массив форм [1 яблоко, 2 яблока, 5 яблок]
     * @return string
     */
    function pluralize_ru(int $count, array $forms): string
    {
        $count = abs($count) % 100;
        $ldigit = $count % 10;

        if ($count > 10 && $count < 20) {
            return $forms[2];
        }
        if ($ldigit > 1 && $ldigit < 5) {
            return $forms[1];
        }
        if ($ldigit == 1) {
            return $forms[0];
        }

        return $forms[2];
    }
}

if (!function_exists('get_user_layout')) {
    /**
     * Get the layout based on the user's role
     *
     * @return string
     */
    function get_user_layout(): string
    {
        $user = auth()->user();

        if (!$user || !$user->role) {
            return 'layouts.app';
        }

        return match ($user->role->value) {
            \App\Constants\RoleConstants::ADMINISTRATOR => 'layouts.admin',
            \App\Constants\RoleConstants::REFEREEING_DEPARTMENT_HEAD => 'layouts.head',
            \App\Constants\RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE => 'layouts.employee',
            \App\Constants\RoleConstants::SOCCER_REFEREE => 'layouts.referee',
            \App\Constants\RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN => 'layouts.logistician',
            \App\Constants\RoleConstants::FINANCE_DEPARTMENT_SPECIALIST => 'layouts.financial-specialist',
            \App\Constants\RoleConstants::FINANCE_DEPARTMENT_HEAD => 'layouts.financial-head',
            \App\Constants\RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT => 'layouts.accountant',
            default => 'layouts.app',
        };
    }
}
