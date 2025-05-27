<?php

namespace Laravel\Nova\Testing\Browser\Components;

use Laravel\Dusk\Browser;

class ActionDropdownComponent extends Component
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return 'div[data-menu-open="true"]';
    }

    /**
     * Run the action with the given URI key.
     *
     * @return void
     */
    public function runWithConfirmation(Browser $browser, string $uriKey)
    {
        $browser->click("button[data-action-id='{$uriKey}']")
            ->elsewhereWhenAvailable(new Modals\ConfirmActionModalComponent, function ($browser) {
                $browser->confirm();
            });
    }

    /**
     * Run the action with the given URI key.
     *
     * @return void
     */
    public function runWithoutConfirmation(Browser $browser, string $uriKey)
    {
        $browser->click("button[data-action-id='{$uriKey}']")
            ->elsewhere('', function ($browser) {
                $browser->assertDontSee('@cancel-action-button');
            });
    }

    /**
     * Open the action modal but cancel the action.
     *
     * @param  callable  $fieldCallback
     * @return void
     */
    public function select(Browser $browser, string $uriKey, $fieldCallback)
    {
        $browser->click("button[data-action-id='{$uriKey}']")
            ->elsewhereWhenAvailable(new Modals\ConfirmActionModalComponent, function ($browser) use ($fieldCallback) {
                $fieldCallback($browser);
            });
    }

    /**
     * Open the action modal but cancel the action.
     *
     * @return void
     */
    public function cancel(Browser $browser, string $uriKey)
    {
        $browser->click("button[data-action-id='{$uriKey}']")
            ->elsewhereWhenAvailable(new Modals\ConfirmActionModalComponent, function ($browser) {
                $browser->cancel();
            });
    }
}
