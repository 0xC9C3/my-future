<?php

namespace Tests\Browser\DashboardTests;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Dashboard\Account;
use Tests\DashboardDuskTestCase;

class AccountTest extends DashboardDuskTestCase
{
    public function testCanSeeAccountForm()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user->id)
                ->visit(new Account)
                ->assertVisible('@email')
                ->assertVisible('@currentPassword')
                ->assertVisible('@password')
                ->assertVisible('@repeatPassword')
                ->assertVisible('@save');
        });
    }

    public function testCanEditAccountForm()
    {
        $this->browse(function (Browser $browser) {

            $values = $this->getValues();

            $browser
                ->loginAs($this->user->id)
                ->visit(new Account);

            $this->clearCustom($browser, '@email');

            $browser->type('@email', $values['@email'])
                ->type('@currentPassword', $values['@currentPassword'])
                ->type('@password', $values['@password'])
                ->type('@repeatPassword', $values['@repeatPassword'])
                ->assertValue('@email', $values['@email'])
                ->assertValue('@currentPassword', $values['@currentPassword'])
                ->assertValue('@password', $values['@password'])
                ->assertValue('@repeatPassword', $values['@repeatPassword']);
        });
    }

    public function testCanSaveAccountForm()
    {
        $this->browse(function (Browser $browser) {
            $values = $this->getValues();

            $browser
                ->loginAs($this->user->id)
                ->visit(new Account);

            $this->clearCustom($browser, '@email');

            $browser->type('@email', $values['@email'])
                ->type('@currentPassword', $values['@currentPassword'])
                ->type('@password', $values['@password'])
                ->type('@repeatPassword', $values['@repeatPassword'])
                ->click('@save')
                ->waitForText('Updated.')
                ->refresh()
                ->assertValue('@email', $values['@email'])
                ->assertValue('@currentPassword', '')
                ->assertValue('@password', '')
                ->assertValue('@repeatPassword', '');
        });
    }

    public function testCanEnableTwoFactorAuth()
    {
        $this->markTestIncomplete();
    }

    public function testCanVerifyTwoFactorAuth()
    {
        $this->markTestIncomplete();
    }

    private function getValues()
    {
        $repeatPassword = $this->faker->password(8);
        return [
            '@email' => $this->faker->email,
            '@currentPassword' => $this->userPassword,
            '@password' => $repeatPassword,
            '@repeatPassword' => $repeatPassword,
        ];
    }
}
