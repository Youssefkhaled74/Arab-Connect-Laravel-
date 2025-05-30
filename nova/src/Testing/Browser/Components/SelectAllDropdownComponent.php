<?php

namespace Laravel\Nova\Testing\Browser\Components;

use Laravel\Dusk\Browser;

class SelectAllDropdownComponent extends Component
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '@select-all-dropdown';
    }

    /**
     * Assert that the checkbox is checked.
     *
     * @return void
     */
    public function assertCheckboxIsChecked(Browser $browser)
    {
        $browser->assertPresent('span.fake-checkbox.fake-checkbox-checked');
    }

    /**
     * Assert that the checkbox is not checked.
     *
     * @return void
     */
    public function assertCheckboxIsNotChecked(Browser $browser)
    {
        $browser->assertPresent('span.fake-checkbox')
            ->assertNotPresent('span.fake-checkbox.fake-checkbox-checked')
            ->assertNotPresent('span.fake-checkbox.fake-checkbox-indeterminate');
    }

    /**
     * Assert that the checkbox is indeterminate.
     *
     * @return void
     */
    public function assertCheckboxIsIndeterminate(Browser $browser)
    {
        $browser->assertPresent('span.fake-checkbox.fake-checkbox-indeterminate');
    }

    /**
     * Assert select all the the resources on current page is checked.
     *
     * @return void
     */
    public function assertSelectAllOnCurrentPageChecked(Browser $browser)
    {
        $this->assertCheckboxIsIndeterminate($browser);
    }

    /**
     * Assert select all the the resources on current page isn't checked.
     *
     * @return void
     */
    public function assertSelectAllOnCurrentPageNotChecked(Browser $browser)
    {
        $browser->assertPresent('span.fake-checkbox')
            ->assertNotPresent('span.fake-checkbox.fake-checkbox-indeterminate');
    }

    /**
     * Assert select all the matching resources is checked.
     *
     * @return void
     */
    public function assertSelectAllMatchingChecked(Browser $browser)
    {
        $this->assertCheckboxIsChecked($browser);
    }

    /**
     * Assert select all the matching resources isn't checked.
     *
     * @return void
     */
    public function assertSelectAllMatchingNotChecked(Browser $browser)
    {
        $browser->assertPresent('span.fake-checkbox')
            ->assertNotPresent('span.fake-checkbox.fake-checkbox-checked');
    }

    /**
     * Assert on the total selected count text.
     *
     * @param  int  $count
     * @return void
     */
    public function assertSelectedCount(Browser $browser, $count)
    {
        $browser->assertSeeIn('span.font-bold', "{$count} selected");
    }

    /**
     * Assert on the matching total matching count text.
     *
     * @param  int  $count
     * @return void
     */
    public function assertSelectAllMatchingCount(Browser $browser, $count)
    {
        $browser->click('')
            ->elsewhereWhenAvailable('[dusk="select-all-matching-count"]', function ($browser) use ($count) {
                $browser->assertSeeIn('', $count);
            })->closeCurrentDropdown();
    }

    /**
     * Select all the the resources on current page.
     *
     * @return void
     */
    public function selectAllOnCurrentPage(Browser $browser)
    {
        $browser->click('')
            ->elsewhereWhenAvailable('[dusk="select-all-button"]', function ($browser) {
                $browser->check('input[type="checkbox"]');
            })
            ->pause(250)
            ->closeCurrentDropdown();
    }

    /**
     * Un-select all the the resources on current page.
     *
     * @return void
     */
    public function unselectAllOnCurrentPage(Browser $browser)
    {
        $browser->click('button')->pause(250);
    }

    /**
     * Select all the matching resources.
     *
     * @return void
     */
    public function selectAllMatching(Browser $browser)
    {
        $browser->click('')
            ->elsewhereWhenAvailable('[dusk="select-all-matching-button"]', function ($browser) {
                $browser->check('input[type="checkbox"]');
            })
            ->pause(250)
            ->closeCurrentDropdown();
    }

    /**
     * Un-select all the matching resources.
     *
     * @return void
     */
    public function unselectAllMatching(Browser $browser)
    {
        $browser->click('button')->pause(250);
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @return void
     *
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    public function assert(Browser $browser)
    {
        tap($this->selector(), function ($selector) use ($browser) {
            $browser->scrollIntoView($selector);
        });
    }
}
