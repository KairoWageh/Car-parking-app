<?php

namespace Database\Seeders;

use App\Models\Zone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Zone::create(["name" => "Green Zone", "price_per_hour" => 100]);
        Zone::create(["name" => "Yellow Zone", "price_per_hour" => 200]);
        Zone::create(["name" => "Red Zone", "price_per_hour" => 300]);
    }
}
