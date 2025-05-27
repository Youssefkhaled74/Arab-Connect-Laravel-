<?php

namespace Laravel\Nova;

use Illuminate\Http\Request;
use Silvanite\NovaToolPermissions\Role;

abstract class Tool
{
    use AuthorizedToSee,
        Makeable,
        ProxiesCanSeeToGate;

    /**
     * Create a new Tool.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the element should be displayed for the given request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return $this->authorizedToSee($request);
    }

    /**
     * Perform any tasks that need to happen on tool registration.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Build the menu that renders the navigation links for the tool.
     *
     * @return mixed
     */
    public function menu(Request $request)
    {
        return [
            Nova::resources([
                Role::class,
            ]),
        ];
    }
}
