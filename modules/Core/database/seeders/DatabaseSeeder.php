<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Core\Models\Announcement\Announcement;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(AnnouncementsTableSeeder::class);

        Announcement::factory(10)->create();
    }
}
