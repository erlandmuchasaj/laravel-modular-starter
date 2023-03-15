<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\Announcement\Announcement;

class AnnouncementsTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Announcement::create([
            'area' => null,
            'type' => 'info',
            'message' => 'This is a <strong>Global</strong> announcement that will show on both the frontend and backend. <em>See <strong>AnnouncementSeeder</strong> for more usage examples.</em>',
            'enabled' => true,
        ]);

        Announcement::create([
            'area' => 'frontend',
            'type' => 'warning',
            'message' => 'This is a <strong>Frontend</strong> announcement that will not show on the backend.',
            'enabled' => true,
        ]);

        Announcement::create([
            'area' => 'backend',
            'type' => 'danger',
            'message' => 'This is a <strong>Backend</strong> announcement that will not show on the frontend.',
            'enabled' => true,
        ]);

        Announcement::create([
            'area' => null,
            'type' => 'danger',
            'message' => 'This announcement will be shown because the current time falls between the start and end dates.',
            'enabled' => true,
            'starts_at' => now()->subWeek(),
            'ends_at' => now()->addWeek(),
        ]);

        Announcement::create([
            'area' => null,
            'type' => 'danger',
            'message' => 'This announcement will not be shown because it is disabled.',
            'enabled' => false,
        ]);

        Announcement::create([
            'area' => null,
            'type' => 'danger',
            'message' => 'This announcement will not be shown because the end date has passed.',
            'enabled' => true,
            'ends_at' => now()->subDay(),
        ]);

        Announcement::create([
            'area' => null,
            'type' => 'danger',
            'message' => 'This announcement will not be shown because the current time does not fall between the start and end dates.',
            'enabled' => true,
            'starts_at' => now()->subWeek(),
            'ends_at' => now()->subDay(),
        ]);
    }
}
