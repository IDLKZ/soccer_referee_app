<?php

namespace Database\Seeders;

use App\Constants\CountryConstants;
use App\Constants\LeagueConstants;
use App\Models\Country;
use App\Models\League;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeagueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kazakhstan = Country::where('value', CountryConstants::KAZAKHSTAN)->first();

        $leagues = [
            [
                'title_ru' => 'Премьер Лига Казахстана',
                'title_kk' => 'Қазақстанның Премьер-Лигасы',
                'title_en' => 'Kazakhstan Premier League',
                'description_ru' => 'Высший дивизион профессионального футбола Казахстана',
                'description_kk' => 'Қазақстанның кәсіби футболының жоғары дивизионы',
                'description_en' => 'Top tier of professional football in Kazakhstan',
                'value' => LeagueConstants::PREMIER_LEAGUE,
                'country_id' => $kazakhstan?->id,
                'level' => 1,
                'is_active' => true,
            ],
            [
                'title_ru' => 'Первая Лига Казахстана',
                'title_kk' => 'Қазақстанның Бірінші Лигасы',
                'title_en' => 'Kazakhstan First League',
                'description_ru' => 'Второй по значимости дивизион футбола Казахстана',
                'description_kk' => 'Қазақстанның екінші маңызды футбол дивизионы',
                'description_en' => 'Second tier of football in Kazakhstan',
                'value' => LeagueConstants::FIRST_LEAGUE,
                'country_id' => $kazakhstan?->id,
                'level' => 2,
                'is_active' => true,
            ],
            [
                'title_ru' => 'Вторая Лига Казахстана',
                'title_kk' => 'Қазақстанның Екінші Лигасы',
                'title_en' => 'Kazakhstan Second League',
                'description_ru' => 'Третий дивизион футбола Казахстана',
                'description_kk' => 'Қазақстанның үшінші футбол дивизионы',
                'description_en' => 'Third tier of football in Kazakhstan',
                'value' => LeagueConstants::SECOND_LEAGUE,
                'country_id' => $kazakhstan?->id,
                'level' => 3,
                'is_active' => true,
            ],
            [
                'title_ru' => 'Женская Лига Казахстана',
                'title_kk' => 'Қазақстанның Әйелдер Лигасы',
                'title_en' => 'Kazakhstan Women\'s League',
                'description_ru' => 'Высший дивизион женского футбола Казахстана',
                'description_kk' => 'Қазақстанның әйелдер футболының жоғары дивизионы',
                'description_en' => 'Top tier of women\'s football in Kazakhstan',
                'value' => LeagueConstants::WOMENS_LEAGUE,
                'country_id' => $kazakhstan?->id,
                'level' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($leagues as $league) {
            League::updateOrCreate(
                ['value' => $league['value']],
                $league
            );
        }
    }
}
