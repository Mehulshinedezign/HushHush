<?php

namespace Database\Seeders;

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
            SettingSeeder::class,
            AdminSettingSeeder::class,
            UserSeeder::class,
            BrandSeeder::class,
            ColorSeeder::class,
            SizeSeeder::class,
            CmsPageSeeder::class,
            CategorySeeder::class,
            NeighborhoodCitySeeder::class,
            CountrySeeder::class,
            StateSeeder::class,
            CitySeeder::class,
        ]);
    }
}
