<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Staff::create([
            "position_id" => 2,
            "email" => "staff@company.com",
            "password" => bcrypt("password"),
            "firstname" => "John",
            "lastname" => "Doe",
            "address" => "Metro Manila",
            "gender" => "male",
            "contact_no" => "1234567890",
            "is_active" => 1
        ]);
    }
}
