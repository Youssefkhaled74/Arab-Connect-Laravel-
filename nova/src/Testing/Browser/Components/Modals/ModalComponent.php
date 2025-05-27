<?php

namespace Laravel\Nova\Testing\Browser\Components\Modals;

use Laravel\Dusk\Browser;
use Laravel\Nova\Testing\Browser\Components\Component;

abstract class ModalComponent extends Component
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '.modal[data-modal-open=true]';
    }

    /**
     * Modal confirmation button.
     *
     * @return void
     */
    abstract public function confirm(Browser $browser);

    /**
     * Modal cancelation button.
     *
     * @return void
     */
    abstract public function cancel(Browser $browser);
}
