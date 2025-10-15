<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = [
            'ru' => ['Москва', 'Санкт-Петербург', 'Новосибирск', 'Екатеринбург', 'Казань', 'Нижний Новгород', 'Челябинск', 'Самара', 'Ростов-на-Дону', 'Уфа'],
            'kk' => ['Мәскеу', 'Санкт-Петербург', 'Новосібір', 'Екатеринбург', 'Қазан', 'Төменгі Новгород', 'Челябі', 'Самара', 'Ростов-на-Дону', 'Ұфа'],
            'en' => ['Moscow', 'Saint Petersburg', 'Novosibirsk', 'Yekaterinburg', 'Kazan', 'Nizhny Novgorod', 'Chelyabinsk', 'Samara', 'Rostov-on-Don', 'Ufa']
        ];

        $index = $this->faker->numberBetween(0, 9);

        return [
            'country_id' => Country::inRandomOrder()->first()->id ?? 1,
            'title_ru' => $cities['ru'][$index],
            'title_kk' => $cities['kk'][$index],
            'title_en' => $cities['en'][$index],
            'value' => $this->faker->unique()->slug(2),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
