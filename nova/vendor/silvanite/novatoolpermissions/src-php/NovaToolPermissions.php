<?php

namespace Silvanite\NovaToolPermissions;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;
use Silvanite\NovaToolPermissions\Role;

class NovaToolPermissions extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::resources([
            Role::class,
        ]);
    }

    

    
}
