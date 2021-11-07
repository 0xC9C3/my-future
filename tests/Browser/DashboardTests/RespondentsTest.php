<?php

namespace Tests\Browser\DashboardTests;

use App\Models\Respondent;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\Respondents\CreateRespondentModal;
use Tests\Browser\Components\Respondents\DeleteRespondentModal;
use Tests\Browser\Pages\Dashboard\Account;
use Tests\Browser\Pages\Dashboard\Respondents;
use Tests\DashboardDuskTestCase;
use Tests\DuskTestCase;

class RespondentsTest extends DashboardDuskTestCase
{
    public function testRenderRespondentTable()
    {
        $respondents = Respondent::factory()->count(5)->create([
            'user_id' => $this->user->id,
        ]);

        $this->browse(function (Browser $browser) use ($respondents) {
            $browser
                ->loginAs($this->user->id)
                ->visit(new Respondents);

            foreach ($respondents as $respondent) {
                $browser->assertSee($respondent->name);
                $browser->assertSee($respondent->email);
            }
        });
    }

    public function testAddRespondent()
    {
        $this->browse(function (Browser $browser) {
            $respondent = [
                'name' => $this->faker->name,
                'email' => $this->faker->email,
                'active' => $this->faker->boolean
            ];

            $browser
                ->loginAs($this->user->id)
                ->visit(new Respondents)
                ->click('@add')
                ->within(new CreateRespondentModal, function (Browser $browser) use ($respondent) {
                    $browser
                        ->type('@name', $respondent['name'])
                        ->type('@email', $respondent['email']);

                    if ($respondent['active']) {
                        $browser->click('@activeCheckbox');
                    }

                    $browser
                        ->assertValue('@name', $respondent['name'])
                        ->assertValue('@email', $respondent['email'])
                        ->assertValue('@activeInput', $respondent['active'] ? 'false' : 'true')
                        ->click('@create');
                })
                ->waitForText($respondent['name'])
                ->assertSee($respondent['email']);
        });
    }

    public function testToggleActive()
    {
        $respondent = Respondent::factory()->create([
            'user_id' => $this->user->id,
            'active' => false,
        ]);

        $this->browse(function (Browser $browser) use ($respondent) {
            $browser
                ->loginAs($this->user->id)
                ->visit(new Respondents)
                ->assertSee($respondent->name)
                ->click('@row .chakra-switch__track')
                ->waitForText('Enabled ' . $respondent->name);
        });
    }

    public function testDelete()
    {
        $this->browse(function (Browser $browser) {
            $respondent = Respondent::factory()->create([
                'user_id' => $this->user->id,
            ]);

            $browser
                ->loginAs($this->user->id)
                ->visit(new Respondents)
                ->click('@row @td @delete')
                ->within(new DeleteRespondentModal, function (Browser $browser) use ($respondent) {
                    $browser->waitForText('Delete ' . $respondent->name);
                    $browser->click('@delete');
                })
                ->waitForText('Deleted ' . $respondent->name);
        });
    }
}
