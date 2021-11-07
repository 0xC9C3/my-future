<?php

namespace Tests\Browser\Pages\Dashboard;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class Respondents extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/dashboard/respondents';
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
            '@row' => 'tr[role="row"]',
            '@td' => 'td[role="gridcell"]',
            '@delete' => 'button[aria-label="delete"]',
            '@add' => '.pt-6 > button[type="button"]',
        ];
    }
}
