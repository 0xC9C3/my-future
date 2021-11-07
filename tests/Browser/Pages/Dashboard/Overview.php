<?php

namespace Tests\Browser\Pages\Dashboard;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class Overview extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/dashboard/overview';
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
            '@sent' => '.chakra-stat:nth-child(1) dd',
            '@viewed' => '.chakra-stat:nth-child(2) dd',
            '@clicked' => '.chakra-stat:nth-child(3) dd',
            '@row' => 'tbody tr[role="row"]',
        ];
    }
}
