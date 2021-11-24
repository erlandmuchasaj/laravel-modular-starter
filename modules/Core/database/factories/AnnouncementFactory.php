<?php

namespace Modules\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AnnouncementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string|null
     */
    protected $model;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'area' => $this->faker->randomElement(['frontend', 'backend', null]),
            'type' => $this->faker->randomElement(['success', 'danger', 'warning', 'info']),
            'message' => $this->faker->realText(180),
            'enabled' => $this->faker->boolean(),
            'ends_at' => now()->subDay()
        ];
    }
}
