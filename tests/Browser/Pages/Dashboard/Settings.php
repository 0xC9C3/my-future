<?php

namespace Tests\Browser\Pages\Dashboard;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class Settings extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/dashboard/settings';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@smtpHost' => 'input[name="smtp_host"]',
            '@smtpPort' => 'input[name="smtp_port"]',
            '@smtpUsername' => 'input[name="smtp_username"]',
            '@smtpPassword' => 'input[name="smtp_password"]',
            '@smtpEncryption' => 'select[name="smtp_encryption"]',
            '@smtpFromAddress' => 'input[name="smtp_from_address"]',
            '@smtpFromName' => 'input[name="smtp_from_name"]',
            '@trackWebAccessInput' => 'input[name="track_web_access"]',
            '@trackEmailAccessInput' => 'input[name="track_email_access"]',
            '@trackWebAccessCheckbox' => 'input[name="track_web_access"] + span',
            '@trackEmailAccessCheckbox' => 'input[name="track_email_access"] + span',
            '@save' => 'button[type="submit"]',
        ];
    }
}
