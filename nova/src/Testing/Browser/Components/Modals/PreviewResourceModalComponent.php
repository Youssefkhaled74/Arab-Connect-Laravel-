<?php

namespace Laravel\Nova\Testing\Browser\Components\Modals;

use Laravel\Dusk\Browser;

class PreviewResourceModalComponent extends ModalComponent
{
    /**
     * Modal confirmation button.
     *
     * @return void
     */
    public function confirm(Browser $browser)
    {
        $browser->click('@confirm-preview-button');
    }

    /**
     * Modal cancelation button.
     *
     * @return void
     */
    public function cancel(Browser $browser)
    {
        $browser->click('@confirm-preview-button');
    }

    /**
     * Modal view detail button.
     *
     * @return void
     */
    public function view(Browser $browser)
    {
        $browser->click('@detail-preview-button');
    }

    /**
     * Assert modal view detail button is visible.
     *
     * @return void
     */
    public function assertViewButtonVisible(Browser $browser)
    {
        $browser->assertVisible('@detail-preview-button');
    }
}
