<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Respondent;
use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Application::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'template_id' => Template::factory(),
            'user_id' => User::factory(),
            'respondent_id' => Respondent::factory(),
            'email_subject' => $this->faker->text(250),
            'email_content' => $this->faker->randomHtml,
            'email_attach_portfolio' => $this->faker->boolean,
        ];
    }
}
