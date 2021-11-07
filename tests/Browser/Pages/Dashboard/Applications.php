<?php

namespace Tests\Browser\Pages\Dashboard;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class Applications extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/dashboard/applications';
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
            '@add' => '.pt-6 button',
            '@delete' => 'button[aria-label="delete"]',
            '@resend' => 'button[aria-label="resend"]',
            '@copy' => 'button[aria-label="copy"]',
            '@history' => 'button[aria-label="history"]',
            '@rightMostModalButton' => 'button[type="button"] + button',
        ];
    }
}
