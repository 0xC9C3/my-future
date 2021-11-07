<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Statistic;
use Illuminate\Database\Eloquent\Factories\Factory;

class StatisticFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Statistic::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'application_id' => Application::factory(),
            'action' => $this->faker->randomElement(['viewed', 'clicked', 'sent'])
        ];
    }
}
