<?php

namespace App\Nova\Dashboards;
use App\Nova\Metrics\BlogCount;
use App\Nova\Metrics\UserCount;
use App\Nova\Metrics\OwnerCount;
use App\Nova\Metrics\BranchCount;
use App\Nova\Metrics\CategoryCount;
use App\Nova\Metrics\PaymentMethodCount;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            new UserCount,
            new OwnerCount,
            new BranchCount,
            new CategoryCount,
            new PaymentMethodCount,
            new BlogCount,
        ];
    }
}
