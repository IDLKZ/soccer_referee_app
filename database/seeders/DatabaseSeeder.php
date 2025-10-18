<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CountrySeeder::class,
            CitySeeder::class,
            JudgeTypeSeeder::class,
            SeasonSeeder::class,
            ClubTypeSeeder::class,
            CategoryOperationSeeder::class,
            OperationSeeder::class,
            LeagueSeeder::class,
            StadiumSeeder::class,
            ClubSeeder::class,
            HotelSeeder::class,
            UserSeeder::class,
            FilemanagerRootFolderSeeder::class,
            TransportTypeSeeder::class,
            FacilitySeeder::class,
            HotelRoomSeeder::class,
        ]);
    }
}
