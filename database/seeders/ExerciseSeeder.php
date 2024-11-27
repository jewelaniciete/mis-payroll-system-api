<?php

namespace Database\Seeders;

use App\Models\Exercise;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Exercise::create([
            "name" => "Gym Workout per Session",
            "price" => 80.00,
            "plan" => "session",
        ]);

        Exercise::create([
            "name" => "Gym Workout per Month",
            "price" => 1200.00,
            "plan" => "monthly",
        ]);

        Exercise::create([
            "name" => "Treadmill per Session",
            "price" => 100.00,
            "plan" => "session",
        ]);

        Exercise::create([
            "name" => "Treadmill per Month",
            "price" => 1200.00,
            "plan" => "session",
        ]);

        Exercise::create([
            "name" => "P.I per Session",
            "price" => 100.00,
            "plan" => "session",
        ]);

        Exercise::create([
            "name" => "P.I per Month",
            "price" => 1200.00,
            "plan" => "session",
        ]);

        Exercise::create([
            "name" => "Boxing per Session",
            "price" => 100.00,
            "plan" => "session",
        ]);

        Exercise::create([
            "name" => "Boxing per Month",
            "price" => 1200.00,
            "plan" => "session",
        ]);
    }
}
