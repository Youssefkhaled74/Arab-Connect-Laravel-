<?php

namespace Laravel\Nova\Testing\Browser\Concerns;

use Laravel\Dusk\Browser;
use Laravel\Nova\Testing\Browser\Components\Modals\CreateRelationModalComponent;

trait InteractsWithInlineCreateRelation
{
    /**
     * Run the inline relation.
     *
     * @return void
     *
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    public function showInlineCreate(Browser $browser, string $uriKey, callable $fieldCallback)
    {
        $browser->whenAvailable("@{$uriKey}-inline-create", function ($browser) use ($fieldCallback) {
            $browser->click('')
                ->elsewhereWhenAvailable(new CreateRelationModalComponent, function ($browser) use ($fieldCallback) {
                    $fieldCallback($browser);
                });
        });
    }

    /**
     * Run the inline create relation.
     *
     * @return void
     *
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    public function runInlineCreate(Browser $browser, string $uriKey, callable $fieldCallback)
    {
        $this->showInlineCreate($browser, $uriKey, function ($browser) use ($fieldCallback) {
            $fieldCallback($browser);

            $browser->click('@create-button')->pause(750);
        });
    }
}
