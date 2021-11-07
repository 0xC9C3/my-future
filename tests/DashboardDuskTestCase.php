<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DuskTestCase;

class DashboardDuskTestCase extends DuskTestCase
{
    use DatabaseMigrations, WithFaker;

    /** @var User $user */
    protected $user;
    protected $userPassword;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userPassword = $this->faker->password;
        $this->user = User::factory()->create([
            'email' => $this->faker->email,
            'password' => \Hash::make($this->userPassword),
        ]);
    }
}
