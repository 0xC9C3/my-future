<?php

namespace Tests\Browser\DashboardTests;

use App\Models\Template;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Dashboard\Templates;
use Tests\DashboardDuskTestCase;
use Tests\DuskTestCase;

class TemplatesTest extends DashboardDuskTestCase
{
    public function testRenderEmailsTemplateTable()
    {
        /** @var Template[] $templates */
        $templates = Template::factory()
            ->count(5)
            ->for($this->user)
            ->create([
                'type' => 'email'
            ]);

        $this->browse(function (Browser $browser) use ($templates) {
            $browser
                ->loginAs($this->user->id)
                ->visit(new Templates);

            foreach ($templates as $template) {
                $browser->assertSee($template->name);
            }
        });
    }

    public function testRenderPortfoliosTemplateTable()
    {
        /** @var Template[] $templates */
        $templates = Template::factory()
            ->count(5)
            ->for($this->user)
            ->create([
                'type' => 'portfolio'
            ]);

        $this->browse(function (Browser $browser) use ($templates) {
            $browser
                ->loginAs($this->user->id)
                ->visit(new Templates);

            foreach ($templates as $template) {
                $browser->assertSee($template->name);
            }
        });
    }

    public function testUploadPortfolioTemplate()
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
                ->attach('@file', __DIR__ . '/../test-assets/html-portfolio.zip')
                ->click('@rightMostModalButton')
                ->with('@portfolios', function (Browser $portfolios) use ($templateName) {
                    $portfolios->waitForText($templateName);
                });
        });
    }

    public function testUploadEmailTemplate()
    {
        $this->browse(function (Browser $browser) {
            $templateName = $this->faker->name;
            $browser
                ->loginAs($this->user->id)
                ->visit(new Templates)
                ->click('@add')
                ->waitForText('Create a template')
                ->type('@name', $templateName)
                ->select('@type', 'email')
                ->attach('@file', __DIR__ . '/../test-assets/html-portfolio.zip')
                ->click('@rightMostModalButton')
                ->with('@emails', function (Browser $portfolios) use ($templateName) {
                    $portfolios->waitForText($templateName);
                });
        });
    }

    public function testExportPDF()
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
                ->attach('@file', __DIR__ . '/../test-assets/html-portfolio.zip')
                ->click('@rightMostModalButton')
                ->with('@portfolios', function (Browser $portfolios) use ($templateName) {
                    $portfolios->waitForText($templateName)
                        ->click('@export');
                });
        });

        $this->markTestIncomplete();
    }

    public function testUpdate()
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
                ->attach('@file', __DIR__ . '/../test-assets/html-portfolio.zip')
                ->click('@rightMostModalButton')
                ->with('@portfolios', function (Browser $portfolios) use ($templateName) {
                    $portfolios->waitForText($templateName)
                        ->click('@update');
                })
                ->attach('@file', __DIR__ . '/../test-assets/html-portfolio.zip')
                ->click('@rightMostModalButton')
                ->waitForText('Updated');
        });
    }

    public function testDelete()
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
                ->attach('@file', __DIR__ . '/../test-assets/html-portfolio.zip')
                ->click('@rightMostModalButton')
                ->with('@portfolios', function (Browser $portfolios) use ($templateName) {
                    $portfolios->waitForText($templateName)
                        ->click('@delete');
                })
                ->click('@rightMostModalButton')
                ->waitForText('Deleted ' . $templateName);
        });
    }

    public function testPreview()
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
                ->attach('@file', __DIR__ . '/../test-assets/html-portfolio.zip')
                ->click('@rightMostModalButton')
                ->with('@portfolios', function (Browser $portfolios) use ($templateName) {
                    $portfolios->waitForText($templateName)
                        ->click('@preview');
                });
        });

        $this->markTestIncomplete();
    }
}
