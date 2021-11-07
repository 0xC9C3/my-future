<?php

namespace Tests\Browser\DashboardTests;

use App\Models\Application;
use App\Models\Respondent;
use App\Models\Statistic;
use App\Models\Template;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Dashboard\Applications;
use Tests\DashboardDuskTestCase;
use Tests\DuskTestCase;

class ApplicationsTest extends DashboardDuskTestCase
{
    public function testRenderApplicationsTable()
    {
        /** @var Application[] $applications */
        $applications = Application::factory()
            ->count(5)
            ->for($this->user)
            ->for(Template::factory())
            ->for(Respondent::factory())
            ->create();

        $this->browse(function (Browser $browser) use ($applications) {
            $browser
                ->loginAs($this->user->id)
                ->visit(new Applications);

            foreach ($applications as $application) {
                $browser->assertSee($application->respondent->name);
                $browser->assertSee($application->template->name);
            }
        });
    }

    public function testApplicationLink()
    {
        /** @var Application $application */
        Application::factory()
            ->count(1)
            ->for($this->user)
            ->for(Template::factory())
            ->for(Respondent::factory())
            ->create();

        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user->id)
                ->visit(new Applications)
                ->click('@copy')
                ->waitForText('Copied');
        });
    }

    public function testCanDeleteApplication()
    {
        /** @var Application $application */
        $application = Application::factory()
            ->for($this->user)
            ->for(Template::factory())
            ->for(Respondent::factory())
            ->create();

        $this->browse(function (Browser $browser) use ($application) {
            $browser
                ->loginAs($this->user->id)
                ->visit(new Applications)
                ->click('@delete')
                ->waitForText('Delete application?')
                ->click('@rightMostModalButton')
                ->waitForText('Deleted')
                ->assertDontSee($application->respondent->name);
        });
    }

    public function testResendApplication()
    {
        Application::factory()
            ->count(1)
            ->for($this->user)
            ->for(Template::factory())
            ->for(Respondent::factory())
            ->create();

        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user->id)
                ->visit(new Applications)
                ->click('@resend')
                ->waitForText('Resend application?')
                ->click('@rightMostModalButton')
                ->waitForText(__('mail_send_failed'));
        });

        $this->markTestIncomplete();
    }

    public function testAddButton()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs($this->user->id)
                ->visit(new Applications)
                ->click('@add')
                ->waitForLocation('/dashboard/apply');
        });
    }

    public function testCanSeeHistory()
    {
        /** @var Application $application */
        $application = Application::factory()
            ->for($this->user)
            ->for(Template::factory())
            ->for(Respondent::factory())
            ->has(Statistic::factory()->count(3))
            ->create();

        $this->browse(function (Browser $browser) use ($application) {
            $browser
                ->loginAs($this->user->id)
                ->visit(new Applications)
                ->click('@history')
                ->waitForText('Application History');

            foreach ($application->statistics as $statistic) {
                $browser->assertSee($statistic->action);
            }
        });
    }
}
