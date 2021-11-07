<?php

namespace Tests\Browser\Pages\Dashboard;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class Account extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/dashboard/account';
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
            '@email' => 'input[name="email"]',
            '@currentPassword' => 'input[name="current_password"]',
            '@password' => 'input[name="password"]',
            '@repeatPassword' => 'input[name="password_confirmation"]',
            '@save' => 'button[type="submit"]',
        ];
    }
}
