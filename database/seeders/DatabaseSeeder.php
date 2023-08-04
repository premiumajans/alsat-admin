<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            CategorySeeder::class,
            CitySeeder::class,
            PermissionsSeeder::class,
            UserSeeder::class,
            AdminSeeder::class,
            PackageSeeder::class,
            TermSeeder::class,
            AdvertSeeder::class,
        ]);
    }
}
