<?php

namespace Tests\Browser\Components\Respondents;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class CreateRespondentModal extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return 'section[role="dialog"]';
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertVisible($this->selector());
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@name' => 'input[name="name"]',
            '@email' => 'input[name="email"]',
            '@activeInput' => 'input[name="active"]',
            '@activeCheckbox' => 'input[name="active"] + span',
            '@create' => 'button[type="submit"]'
        ];
    }
}
