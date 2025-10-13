<?php

namespace Database\Seeders;

use App\Constants\RoleConstants;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('admin123');

        // Получаем ID ролей
        $adminRole = Role::where('value', RoleConstants::ADMINISTRATOR)->first();
        $employeeRole = Role::where('value', RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE)->first();
        $headRole = Role::where('value', RoleConstants::REFEREEING_DEPARTMENT_HEAD)->first();
        $financeSpecialistRole = Role::where('value', RoleConstants::FINANCE_DEPARTMENT_SPECIALIST)->first();
        $financeHeadRole = Role::where('value', RoleConstants::FINANCE_DEPARTMENT_HEAD)->first();
        $refereeRole = Role::where('value', RoleConstants::SOCCER_REFEREE)->first();
        $logisticianRole = Role::where('value', RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN)->first();
        $accountantRole = Role::where('value', RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT)->first();

        $users = [
            // 1. Администратор
            [
                'role_id' => $adminRole?->id,
                'last_name' => 'Иванов',
                'first_name' => 'Алексей',
                'patronomic' => 'Петрович',
                'phone' => '+77011234567',
                'email' => 'admin@example.com',
                'username' => 'admin',
                'sex' => 1,
                'iin' => '850215300123',
                'birth_date' => '1985-02-15',
                'password_hash' => $password,
                'is_active' => true,
                'is_verified' => true,
            ],
            // 2. Сотрудник департамента футбольного судейства
            [
                'role_id' => $employeeRole?->id,
                'last_name' => 'Смирнов',
                'first_name' => 'Дмитрий',
                'patronomic' => 'Александрович',
                'phone' => '+77012345678',
                'email' => 'smirnov@example.com',
                'username' => 'dsmirnov',
                'sex' => 1,
                'iin' => '900512300456',
                'birth_date' => '1990-05-12',
                'password_hash' => $password,
                'is_active' => true,
                'is_verified' => true,
            ],
            // 3. Руководитель департамента футбольного судейства
            [
                'role_id' => $headRole?->id,
                'last_name' => 'Нурсултанов',
                'first_name' => 'Ерлан',
                'patronomic' => 'Асанович',
                'phone' => '+77013456789',
                'email' => 'nursultanov@example.com',
                'username' => 'enursultanov',
                'sex' => 1,
                'iin' => '780820300789',
                'birth_date' => '1978-08-20',
                'password_hash' => $password,
                'is_active' => true,
                'is_verified' => true,
            ],
            // 4. Специалист финансового департамента
            [
                'role_id' => $financeSpecialistRole?->id,
                'last_name' => 'Петрова',
                'first_name' => 'Анна',
                'patronomic' => 'Сергеевна',
                'phone' => '+77014567890',
                'email' => 'petrova@example.com',
                'username' => 'apetrova',
                'sex' => 2,
                'iin' => '920310400123',
                'birth_date' => '1992-03-10',
                'password_hash' => $password,
                'is_active' => true,
                'is_verified' => true,
            ],
            // 5. Руководитель финансового департамента
            [
                'role_id' => $financeHeadRole?->id,
                'last_name' => 'Абдуллаев',
                'first_name' => 'Тимур',
                'patronomic' => 'Маратович',
                'phone' => '+77015678901',
                'email' => 'abdullaev@example.com',
                'username' => 'tabdullaev',
                'sex' => 1,
                'iin' => '801125300456',
                'birth_date' => '1980-11-25',
                'password_hash' => $password,
                'is_active' => true,
                'is_verified' => true,
            ],
            // 6-10. Футбольные судьи (5 человек)
            [
                'role_id' => $refereeRole?->id,
                'last_name' => 'Касымов',
                'first_name' => 'Бауыржан',
                'patronomic' => 'Серикович',
                'phone' => '+77016789012',
                'email' => 'kasymov@example.com',
                'username' => 'bkasymov',
                'sex' => 1,
                'iin' => '880405300789',
                'birth_date' => '1988-04-05',
                'password_hash' => $password,
                'is_active' => true,
                'is_verified' => true,
            ],
            [
                'role_id' => $refereeRole?->id,
                'last_name' => 'Жумабаев',
                'first_name' => 'Арман',
                'patronomic' => 'Болатович',
                'phone' => '+77017890123',
                'email' => 'zhumabaev@example.com',
                'username' => 'azhumabaev',
                'sex' => 1,
                'iin' => '910718300456',
                'birth_date' => '1991-07-18',
                'password_hash' => $password,
                'is_active' => true,
                'is_verified' => true,
            ],
            [
                'role_id' => $refereeRole?->id,
                'last_name' => 'Сейдахметов',
                'first_name' => 'Нурлан',
                'patronomic' => 'Амангельдыевич',
                'phone' => '+77018901234',
                'email' => 'seidakhmetov@example.com',
                'username' => 'nseidakhmetov',
                'sex' => 1,
                'iin' => '860922300789',
                'birth_date' => '1986-09-22',
                'password_hash' => $password,
                'is_active' => true,
                'is_verified' => true,
            ],
            [
                'role_id' => $refereeRole?->id,
                'last_name' => 'Тулеуов',
                'first_name' => 'Ержан',
                'patronomic' => 'Даулетович',
                'phone' => '+77019012345',
                'email' => 'tuleuov@example.com',
                'username' => 'etuleuov',
                'sex' => 1,
                'iin' => '891230300123',
                'birth_date' => '1989-12-30',
                'password_hash' => $password,
                'is_active' => true,
                'is_verified' => true,
            ],
            [
                'role_id' => $refereeRole?->id,
                'last_name' => 'Искаков',
                'first_name' => 'Асхат',
                'patronomic' => 'Муратович',
                'phone' => '+77010123456',
                'email' => 'iskakov@example.com',
                'username' => 'aiskakov',
                'sex' => 1,
                'iin' => '930606300456',
                'birth_date' => '1993-06-06',
                'password_hash' => $password,
                'is_active' => true,
                'is_verified' => true,
            ],
            // 11. Логист департамента
            [
                'role_id' => $logisticianRole?->id,
                'last_name' => 'Ахметова',
                'first_name' => 'Айгуль',
                'patronomic' => 'Нурлановна',
                'phone' => '+77011234560',
                'email' => 'akhmetova@example.com',
                'username' => 'aakhmetova',
                'sex' => 2,
                'iin' => '940815400789',
                'birth_date' => '1994-08-15',
                'password_hash' => $password,
                'is_active' => true,
                'is_verified' => true,
            ],
            // 12. Бухгалтер департамента
            [
                'role_id' => $accountantRole?->id,
                'last_name' => 'Кузнецова',
                'first_name' => 'Елена',
                'patronomic' => 'Викторовна',
                'phone' => '+77012345601',
                'email' => 'kuznetsova@example.com',
                'username' => 'ekuznetsova',
                'sex' => 2,
                'iin' => '870427400123',
                'birth_date' => '1987-04-27',
                'password_hash' => $password,
                'is_active' => true,
                'is_verified' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
