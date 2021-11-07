<?php

namespace Tests\Browser\DashboardTests;

use App\Models\Application;
use App\Models\Respondent;
use App\Models\Template;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Dashboard\Apply;
use Tests\Browser\Pages\Dashboard\Templates;
use Tests\DashboardDuskTestCase;
use Tests\DuskTestCase;

class ApplyTest extends DashboardDuskTestCase
{
    public function testNoTemplatesConfigured()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user->id)
                ->visit(new Apply)
                ->assertSee('No template configured');
        });
    }

    public function testWithoutSendingMail()
    {
        /** @var Respondent $respondent */
        $respondent = Respondent::factory()->for($this->user)->create();
        /** @var Template $template */
        $template = Template::factory()->for($this->user)->create(['type' => 'portfolio']);

        $this->browse(function (Browser $browser) use ($respondent, $template) {
            $browser
                ->loginAs($this->user->id)
                ->visit(new Apply)
                ->type('@respondentName', $respondent->name)
                ->type('@respondentEmail', $respondent->email)
                ->click('@next')
                ->select('@portfolioId', $template->id)
                ->click('@next')
                ->select('@emailSend', '')
                ->click('@next')
                ->click('@apply')
                ->waitForText('Application saved');
        });
    }

    public function testWithSendingMailText()
    {
        /** @var Respondent $respondent */
        $respondent = Respondent::factory()->for($this->user)->create();
        /** @var Template $template */
        $template = Template::factory()->for($this->user)->create(['type' => 'portfolio']);
        /** @var Application $application */
        $application = Application::factory()->make();

        $this->browse(function (Browser $browser) use ($respondent, $template, $application) {
            $browser
                ->loginAs($this->user->id)
                ->visit(new Apply)
                ->type('@respondentName', $respondent->name)
                ->type('@respondentEmail', $respondent->email)
                ->click('@next')
                ->select('@portfolioId', $template->id)
                ->click('@next')
                ->select('@emailSend', '1')
                ->type('@emailSubject', $application->email_subject)
                ->select('@emailUseTemplate', '')
                ->type('@emailContent', $application->email_content)
                ->click('@next')
                ->click('@apply')
                ->waitForText(__('mail_send_failed'));
        });

        $this->markTestIncomplete();
    }

    public function testWithSendingMailHTMLTemplate()
    {
        /** @var Respondent $respondent */
        $respondent = Respondent::factory()->for($this->user)->create();
        /** @var Template $template */
        $template = Template::factory()->for($this->user)->create(['type' => 'portfolio']);
        /** @var Application $application */
        $application = Application::factory()->make();

        $this->browse(function (Browser $browser) use ($respondent, $template, $application) {
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
                })
                ->visit(new Apply)
                ->type('@respondentName', $respondent->name)
                ->type('@respondentEmail', $respondent->email)
                ->click('@next')
                ->select('@portfolioId', $template->id)
                ->click('@next')
                ->select('@emailSend', '1')
                ->type('@emailSubject', $application->email_subject)
                ->select('@emailUseTemplate', '1')
                ->select('@emailTemplateId')
                ->waitForText('A Web Page')
                ->click('@next')
                ->click('@apply')
                ->assertSee('A Web Page')
                ->waitForText(__('mail_send_failed'));
        });
    }
}
