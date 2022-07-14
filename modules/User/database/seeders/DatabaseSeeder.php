<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{

    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->command->line('Start seeding the DB....');

        $this->call(UserTableSeeder::class);

        \Modules\User\Models\User\User::factory(25)->create();
        // User::factory(25)->create();

        $this->command->info('All Database tables have been seeded.');
    }
}
