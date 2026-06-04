<?php

namespace Database\Seeders;

use App\Models\Rank;
use Illuminate\Database\Seeder;

class RankSeeder extends Seeder
{
    public function run(): void
    {
        Rank::create(['title' => 'IT Trainee', 'required_xp' => 0]);
        Rank::create(['title' => 'IT Explorer', 'required_xp' => 100]);
        Rank::create(['title' => 'IT Specialist', 'required_xp' => 300]);
        Rank::create(['title' => 'IT Expert', 'required_xp' => 700]);
        Rank::create(['title' => 'IT Master', 'required_xp' => 1200]);
        Rank::create(['title' => 'IT Legend', 'required_xp' => 2000]);
    }
}
