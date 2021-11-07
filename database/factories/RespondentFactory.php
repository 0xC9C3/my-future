<?php

namespace Database\Factories;

use App\Models\Respondent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RespondentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Respondent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'active' => $this->faker->boolean,
        ];
    }
}
