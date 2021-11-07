<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Login;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations, WithFaker;

    public function testCanSeeLoginForm()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Login)
                ->assertVisible('@email')
                ->assertVisible('@password')
                ->assertVisible('@rememberCheckbox');
        });
    }

    public function testCanEditLoginForm()
    {
        $emailInput = $this->faker->text(15);
        $passwordInput = $this->faker->text(15);
        $this->browse(function (Browser $browser) use ($emailInput, $passwordInput) {
            $browser->visit(new Login)
                ->type('@email', $emailInput)
                ->type('@password', $passwordInput)
                ->click('@rememberCheckbox')
                ->assertValue('@email', $emailInput)
                ->assertValue('@password', $passwordInput)
                ->assertValue('@rememberInput', 'true');
        });
    }

    public function testCanFailLoginAttempt()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Login)
                ->type('@email', 'thisemailshould@not.exist')
                ->type('@password', 'thispasswordalsodoesnotfit')
                ->press('@loginButton')
                ->waitForText(__('auth.failed'));
        });
    }

    public function testCanSuccessfullyLogin()
    {
        $password = $this->faker->password;
        $user = User::factory()->create([
            'email' => $this->faker->email,
            'password' => \Hash::make($password),
        ]);

        $this->browse(function (Browser $browser) use ($user, $password) {
            $browser->visit(new Login)
                ->type('@email', $user->email)
                ->type('@password', $password)
                ->press('@loginButton')
                ->waitForText('Welcome')
                ->logout();
        });
    }

    public function testCanSuccessfullyLoginWith2FA()
    {
        $password = $this->faker->password;
        $user = User::factory()->create([
            'email' => $this->faker->email,
            'password' => \Hash::make($password),
            'two_factor_verified' => true,
            'two_factor_secret' => 'some-secret',
            'two_factor_recovery_codes' => 'some-recovery-codes'
        ]);

        $this->browse(function (Browser $browser) use ($user, $password) {
            $browser->visit(new Login)
                ->type('@email', $user->email)
                ->type('@password', $password)
                ->press('@loginButton')
                ->waitForText('Please enter your one time password.')
                ->assertPathIs('/two-factor-challenge');
        });

        $this->markTestIncomplete();
    }

    public function testLogout()
    {
        $password = $this->faker->password;
        $user = User::factory()->create([
            'email' => $this->faker->email,
            'password' => \Hash::make($password),
        ]);

        $this->browse(function (Browser $browser) use ($user, $password) {
            $browser->visit(new Login)
                ->type('@email', $user->email)
                ->type('@password', $password)
                ->press('@loginButton')
                ->waitForText('Welcome')
                ->click('button[aria-haspopup="menu"]')
                ->click('div[role="menuitem"]')
                ->waitForText('Log in')
                ->assertPathIs('/login');
        });
    }
}
