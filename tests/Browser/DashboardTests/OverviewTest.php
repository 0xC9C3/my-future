<?php

namespace Tests\Browser\DashboardTests;

use App\Models\Application;
use App\Models\Statistic;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Dashboard\Overview;
use Tests\DashboardDuskTestCase;
use Tests\DuskTestCase;

class OverviewTest extends DashboardDuskTestCase
{
    public function testRenderStatistic()
    {
        $values = [
            'sent' => $this->faker->numberBetween(0, 100),
            'viewed' => $this->faker->numberBetween(0, 100),
            'clicked' => $this->faker->numberBetween(0, 100)
        ];

        foreach ($values as $key => $value) {
            Statistic::factory()
                ->count($value)
                ->for(Application::factory()->for($this->user))
                ->create([
                    'action' => $key
                ]);
        }

        $this->browse(function (Browser $browser) use ($values) {
            $browser
                ->loginAs($this->user->id)
                ->visit(new Overview);

            foreach ($values as $key => $value) {
                $browser->assertSeeIn("@$key", $value);
            }
        });
    }

    public function testRenderEventsTable()
    {
        /** @var Statistic[] $statistics */
        $statistics = Statistic::factory()
            ->count($this->faker->numberBetween(0, 100))
            ->for(Application::factory()->for($this->user))
            ->create();

        $this->browse(function (Browser $browser) use ($statistics) {
            $browser
                ->loginAs($this->user->id)
                ->visit(new Overview);

            foreach ($statistics as $statistic) {
                $browser->assertSee($statistic->action)
                    ->assertSee($statistic->application->respondent->name)
                    ->assertSee($statistic->application->template->name);
            }

            $this->assertSameSize($statistics, $browser->elements('@row'));
        });
    }
}
