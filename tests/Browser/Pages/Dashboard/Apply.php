<?php

namespace Tests\Browser\Pages\Dashboard;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class Apply extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/dashboard/apply';
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
            '@respondentName' => 'input[name="respondent.name"]',
            '@respondentEmail' => 'input[name="respondent.email"]',
            '@respondentActive' => 'input[name="respondent.active"]',
            '@portfolioId' => 'select[name="portfolio.id"]',
            '@emailSend' => 'select[name="email.send_email"]',
            '@emailSubject' => 'input[name="email.subject"]',
            '@emailAttachPortfolio' => 'input[name="email.attach_portfolio"]',
            '@emailUseTemplate' => 'select[name="email.use_email_template"]',
            '@emailContent' => 'textarea[name="email.content"]',
            '@emailTemplateId' => 'select[name="email.template_id"]',
            '@previous' => 'form + div button:first-child',
            '@next' => 'form + div button:last-child',
            '@apply' => 'button[type="submit"]',
        ];
    }
}
