<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategoryOperation;
use App\Constants\CategoryOperationConstants;

class CategoryOperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'value' => CategoryOperationConstants::REFEREE_ASSIGNMENT,
                'level' => 1,
                'is_active' => true,
                'title_ru' => 'Назначение судьи',
                'title_kk' => 'Төрешіні тағайындау',
                'title_en' => 'Referee Assignment',
            ],
            [
                'value' => CategoryOperationConstants::BUSINESS_TRIP,
                'level' => 2,
                'is_active' => true,
                'title_ru' => 'Командировка',
                'title_kk' => 'Іссапар',
                'title_en' => 'Business Trip',
            ],
            [
                'value' => CategoryOperationConstants::MATCH_PROTOCOL,
                'level' => 3,
                'is_active' => true,
                'title_ru' => 'Протокол матча',
                'title_kk' => 'Матч хаттамасы',
                'title_en' => 'Match Protocol',
            ],
            [
                'value' => CategoryOperationConstants::AVR,
                'level' => 4,
                'is_active' => true,
                'title_ru' => 'АВР',
                'title_kk' => 'АЕҚ',
                'title_en' => 'AVR',
            ],
            [
                'value' => CategoryOperationConstants::PAYMENT,
                'level' => 5,
                'is_active' => true,
                'title_ru' => 'Оплата',
                'title_kk' => 'Төлем',
                'title_en' => 'Payment',
            ],
            [
                'value' => CategoryOperationConstants::FINAL_RESULT,
                'level' => 6,
                'is_active' => true,
                'title_ru' => 'Конечный результат',
                'title_kk' => 'Соңғы нәтиже',
                'title_en' => 'Final Result',
            ],
        ];

        foreach ($categories as $category) {
            CategoryOperation::updateOrCreate(
                ['value' => $category['value']],
                $category
            );
        }
    }
}
