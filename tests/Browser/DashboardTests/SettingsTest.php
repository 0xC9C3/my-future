<?php

namespace Tests\Browser\DashboardTests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Dashboard\Settings;
use Tests\DashboardDuskTestCase;

class SettingsTest extends DashboardDuskTestCase
{
    public function testCanSeeSettingsForm()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user->id)
                ->visit(new Settings)
                ->assertVisible('@smtpHost')
                ->assertVisible('@smtpPort')
                ->assertVisible('@smtpUsername')
                ->assertVisible('@smtpPassword')
                ->assertVisible('@smtpEncryption')
                ->assertVisible('@smtpFromAddress')
                ->assertVisible('@smtpFromName')
                ->assertVisible('@trackWebAccessCheckbox')
                ->assertVisible('@trackWebAccessInput')
                ->assertVisible('@trackEmailAccessCheckbox')
                ->assertVisible('@trackEmailAccessInput')
                ->assertVisible('@save');
        });
    }

    public function testCanEditSettingsForm()
    {
        $this->browse(function (Browser $browser) {
            $values = $this->getValues();

            $browser
                ->loginAs($this->user->id)
                ->visit(new Settings)
                ->type('@smtpHost', $values['@smtpHost']);
            $this->clearCustom($browser, '@smtpPort');
            $browser->type('@smtpPort', $values['@smtpPort'])
                ->type('@smtpUsername', $values['@smtpUsername'])
                ->type('@smtpPassword', $values['@smtpPassword'])
                ->select('@smtpEncryption', $values['@smtpEncryption'])
                ->type('@smtpFromAddress', $values['@smtpFromAddress'])
                ->type('@smtpFromName', $values['@smtpFromName'])
                ->click('@trackWebAccessCheckbox')
                ->click('@trackEmailAccessCheckbox')
                ->assertValue('@smtpHost', $values['@smtpHost'])
                ->assertValue('@smtpPort', $values['@smtpPort'])
                ->assertValue('@smtpUsername', $values['@smtpUsername'])
                ->assertValue('@smtpPassword', $values['@smtpPassword'])
                ->assertSelected('@smtpEncryption', $values['@smtpEncryption'])
                ->assertValue('@smtpFromAddress', $values['@smtpFromAddress'])
                ->assertValue('@smtpFromName', $values['@smtpFromName'])
                ->assertValue('@trackWebAccessInput', 'true')
                ->assertValue('@trackEmailAccessInput', 'true');
        });
    }

    public function testCanSaveSettingsForm()
    {
        $this->browse(function (Browser $browser) {
            $values = $this->getValues();

            $browser
                ->loginAs($this->user->id)
                ->visit(new Settings)
                ->type('@smtpHost', $values['@smtpHost']);
            $this->clearCustom($browser, '@smtpPort');
            $browser->type('@smtpPort', $values['@smtpPort'])
                ->type('@smtpUsername', $values['@smtpUsername'])
                ->type('@smtpPassword', $values['@smtpPassword'])
                ->select('@smtpEncryption', $values['@smtpEncryption'])
                ->type('@smtpFromAddress', $values['@smtpFromAddress'])
                ->type('@smtpFromName', $values['@smtpFromName'])
                ->click('@trackWebAccessCheckbox')
                ->click('@trackEmailAccessCheckbox')
                ->click('@save')
                ->waitForText('Updated.')
                ->refresh()
                ->assertValue('@smtpHost', $values['@smtpHost'])
                ->assertValue('@smtpPort', $values['@smtpPort'])
                ->assertValue('@smtpUsername', '')
                ->assertValue('@smtpPassword', '')
                ->assertSelected('@smtpEncryption', $values['@smtpEncryption'])
                ->assertValue('@smtpFromAddress', $values['@smtpFromAddress'])
                ->assertValue('@smtpFromName', $values['@smtpFromName'])
                ->assertValue('@trackWebAccessInput', 'true')
                ->assertValue('@trackEmailAccessInput', 'true');
        });
    }

    private function getValues()
    {
        return [
            '@smtpHost' => $this->faker->domainName,
            '@smtpPort' => $this->faker->numberBetween(1, 65535),
            '@smtpUsername' => $this->faker->userName,
            '@smtpPassword' => $this->faker->password,
            '@smtpEncryption' => 'tls',
            '@smtpFromAddress' => $this->faker->email,
            '@smtpFromName' => $this->faker->name,
        ];
    }
}
