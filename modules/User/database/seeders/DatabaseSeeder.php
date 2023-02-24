<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->line('Start seeding the DB....');

        $this->call(UserTableSeeder::class);

        \Modules\User\Models\User\User::factory(25)->create();

        $this->command->info('All Database tables have been seeded.');
    }
}
