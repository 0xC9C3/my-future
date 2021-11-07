<?php

namespace Tests\Browser\Pages\Dashboard;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class Templates extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/dashboard/templates';
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
            '@emails' => '.py-6.flex-grow table',
            '@portfolios' => '.pb-6.flex-grow table',
            '@delete' => 'button[aria-label="delete"]',
            '@download' => 'button[aria-label="download"]',
            '@export' => 'a[aria-label="export"]',
            '@update' => 'button[aria-label="update"]',
            '@preview' => 'a[aria-label="preview"]',
            '@name' => 'input[name="name"]',
            '@type' => 'select[name="type"]',
            '@file' => 'input[name="template"]',
            '@add' => '.pt-6 > button[type="button"]',
            '@rightMostModalButton' => 'button[type="button"] + button',
        ];
    }
}
