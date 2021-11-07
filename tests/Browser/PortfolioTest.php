<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Dashboard\Templates;
use Tests\DashboardDuskTestCase;
use Tests\DuskTestCase;

class PortfolioTest extends DashboardDuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testPreviewPortfolio()
    {
        $this->browse(function (Browser $browser) {
            $templateName = $this->faker->name;
            $browser
                ->loginAs($this->user->id)
                ->visit(new Templates)
                ->click('@add')
                ->waitForText('Create a template')
                ->type('@name', $templateName)
                ->select('@type', 'portfolio')
                ->attach('@file', __DIR__ . '/test-assets/html-portfolio.zip')
                ->click('@rightMostModalButton')
                ->with('@portfolios', function (Browser $portfolios) use ($templateName) {
                    $portfolios->waitForText($templateName);
                });
            $previewLink = $browser->element('@portfolios @preview')->getAttribute('href');
            $browser->visit($previewLink)
                ->assertSee('A Web Page');
        });
    }

    public function testViewPortfolio()
    {
        $this->markTestIncomplete();
    }
}
