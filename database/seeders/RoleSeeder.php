<?php

namespace Database\Seeders;

use App\Constants\RoleConstants;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'title_ru' => 'Администратор',
                'title_kk' => 'Әкімші',
                'title_en' => 'Administrator',
                'description_ru' => 'Полный доступ к системе',
                'description_kk' => 'Жүйеге толық қол жеткізу',
                'description_en' => 'Full system access',
                'value' => RoleConstants::ADMINISTRATOR,
                'is_administrative' => true,
                'is_active' => true,
                'can_register' => false,
                'is_system' => true,
            ],
            [
                'title_ru' => 'Сотрудник департамента футбольного судейства',
                'title_kk' => 'Футбол төрешілігі департаментінің қызметкері',
                'title_en' => 'Refereeing Department Employee',
                'description_ru' => 'Сотрудник, работающий в департаменте футбольного судейства',
                'description_kk' => 'Футбол төрешілігі департаментінде жұмыс істейтін қызметкер',
                'description_en' => 'Employee working in the refereeing department',
                'value' => RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
                'is_administrative' => false,
                'is_active' => true,
                'can_register' => false,
                'is_system' => false,
            ],
            [
                'title_ru' => 'Руководитель департамента футбольного судейства',
                'title_kk' => 'Футбол төрешілігі департаментінің басшысы',
                'title_en' => 'Refereeing Department Head',
                'description_ru' => 'Руководитель департамента футбольного судейства',
                'description_kk' => 'Футбол төрешілігі департаментінің басшысы',
                'description_en' => 'Head of the refereeing department',
                'value' => RoleConstants::REFEREEING_DEPARTMENT_HEAD,
                'is_administrative' => true,
                'is_active' => true,
                'can_register' => false,
                'is_system' => false,
            ],
            [
                'title_ru' => 'Специалист финансового департамента футбольного судейства',
                'title_kk' => 'Футбол төрешілігінің қаржы департаментінің маманы',
                'title_en' => 'Finance Department Specialist',
                'description_ru' => 'Специалист финансового отдела департамента футбольного судейства',
                'description_kk' => 'Футбол төрешілігі департаментінің қаржы бөлімінің маманы',
                'description_en' => 'Specialist of the finance department',
                'value' => RoleConstants::FINANCE_DEPARTMENT_SPECIALIST,
                'is_administrative' => false,
                'is_active' => true,
                'can_register' => false,
                'is_system' => false,
            ],
            [
                'title_ru' => 'Руководитель финансового департамента футбольного судейства',
                'title_kk' => 'Футбол төрешілігінің қаржы департаментінің басшысы',
                'title_en' => 'Finance Department Head',
                'description_ru' => 'Руководитель финансового отдела департамента футбольного судейства',
                'description_kk' => 'Футбол төрешілігі департаментінің қаржы бөлімінің басшысы',
                'description_en' => 'Head of the finance department',
                'value' => RoleConstants::FINANCE_DEPARTMENT_HEAD,
                'is_administrative' => true,
                'is_active' => true,
                'can_register' => false,
                'is_system' => false,
            ],
            [
                'title_ru' => 'Футбольный судья',
                'title_kk' => 'Футбол төрешісі',
                'title_en' => 'Soccer Referee',
                'description_ru' => 'Сертифицированный футбольный судья',
                'description_kk' => 'Сертификатталған футбол төрешісі',
                'description_en' => 'Certified soccer referee',
                'value' => RoleConstants::SOCCER_REFEREE,
                'is_administrative' => false,
                'is_active' => true,
                'can_register' => true,
                'is_system' => false,
            ],
            [
                'title_ru' => 'Логист департамента футбольного судейства',
                'title_kk' => 'Футбол төрешілігі департаментінің логисті',
                'title_en' => 'Refereeing Department Logistician',
                'description_ru' => 'Логист, отвечающий за транспортировку и размещение судей',
                'description_kk' => 'Төрешілердің көлігі мен орналасуына жауапты логист',
                'description_en' => 'Logistician responsible for transportation and accommodation of referees',
                'value' => RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN,
                'is_administrative' => false,
                'is_active' => true,
                'can_register' => false,
                'is_system' => false,
            ],
            [
                'title_ru' => 'Бухгалтер департамента футбольного судейства',
                'title_kk' => 'Футбол төрешілігі департаментінің бухгалтері',
                'title_en' => 'Refereeing Department Accountant',
                'description_ru' => 'Бухгалтер, управляющий финансами департамента',
                'description_kk' => 'Департаменттің қаржысын басқаратын бухгалтер',
                'description_en' => 'Accountant managing department finances',
                'value' => RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT,
                'is_administrative' => false,
                'is_active' => true,
                'can_register' => false,
                'is_system' => false,
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['value' => $roleData['value']],
                $roleData
            );
        }
    }
}
