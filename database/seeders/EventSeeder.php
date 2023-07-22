<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // I'm using chunk in case it's a large dataset, I know it's irrelevant in this case.
        User::query()->chunk(10, function (Collection $users) {
            foreach ($users as $user) {
                Event::factory(50)->create(['organization_id' => $user->id]);
            }
        });
    }
}
