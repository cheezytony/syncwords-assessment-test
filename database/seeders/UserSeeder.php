<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::factory()->create([
            'first_name' => 'Antonio',
            'last_name' => 'Okoro',
            'email' => 'antonio.c.okoro@gmail.com',
        ]);

        $users = User::factory(5)->create();

        // Using the method below would be faster as it wouldn't need to fetch the users again.
        // foreach ($users as $user) {
        //     $user->events()->create([
        //         'event_title' => 'Authentication Event',
        //         'event_start_date' => now(),
        //         'event_end_date' => now(),
        //     ]);
        // }
    }
}
