<?php

namespace Database\Seeders;

use App\Models\TransportType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transportTypes = [
            [
                'title_ru' => 'Автомобиль',
                'title_kk' => 'Автомобиль',
                'title_en' => 'Car',
                'description_ru' => 'Стандартный легковой автомобиль для перевозки 1-4 человек',
                'description_kk' => '1-4 адамды тасымалдауға арналған стандарт жолаушы автокөлігі',
                'description_en' => 'Standard passenger car for 1-4 people transportation',
            ],
            [
                'title_ru' => 'Микроавтобус',
                'title_kk' => 'Микроавтобус',
                'title_en' => 'Minivan',
                'description_ru' => 'Микроавтобус для перевозки 5-8 человек',
                'description_kk' => '5-8 адамды тасымалдауға арналған микроавтобус',
                'description_en' => 'Minivan for 5-8 people transportation',
            ],
            [
                'title_ru' => 'Автобус',
                'title_kk' => 'Автобус',
                'title_en' => 'Bus',
                'description_ru' => 'Автобус для перевозки большой группы судей (9+ человек)',
                'description_kk' => 'Үлкен төрешілер тобын тасымалдауға арналған автобус (9+ адам)',
                'description_en' => 'Bus for large referee group transportation (9+ people)',
            ],
            [
                'title_ru' => 'Самолет',
                'title_kk' => 'Ұшақ',
                'title_en' => 'Airplane',
                'description_ru' => 'Авиаперелет для дальних расстояний',
                'description_kk' => 'Қашықтықтарға авиа ұшуы',
                'description_en' => 'Flight for long distances',
            ],
            [
                'title_ru' => 'Поезд',
                'title_kk' => 'Поезд',
                'title_en' => 'Train',
                'description_ru' => 'Железнодорожный транспорт для междугородних поездок',
                'description_kk' => 'Қалааралық сапарларға арналған темір жол көлігі',
                'description_en' => 'Railway transport for intercity trips',
            ],
            [
                'title_ru' => 'Такси',
                'title_kk' => 'Такси',
                'title_en' => 'Taxi',
                'description_ru' => 'Такси для индивидуальных поездок',
                'description_kk' => 'Жекелік сапарларға арналған такси',
                'description_en' => 'Taxi for individual trips',
            ],
        ];

        foreach ($transportTypes as $transportTypeData) {
            TransportType::updateOrCreate(
                ['title_ru' => $transportTypeData['title_ru']],
                $transportTypeData
            );
        }
    }
}