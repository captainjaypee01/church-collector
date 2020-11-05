<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FakeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \App\Models\User::factory()->count(150)->members()->create();
        \App\Models\User::factory()->count(50)->leaders()->create();
        \App\Models\Collection::factory()->count(10)->pledges()->create();
        \App\Models\Location::factory()->count(10)->create();
    }
}
