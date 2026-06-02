<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            DivisiSeeder::class,
            JabatanSeeder::class,
            JenisTrainingSeeder::class,
            PegawaiSeeder::class,
            TrainingSeeder::class,
            PegawaiTrainingSeeder::class,
        ]);
    }
}