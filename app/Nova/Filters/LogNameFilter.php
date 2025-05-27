<?php

namespace App\Nova\Filters;

use Laravel\Nova\Filters\Filter;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogNameFilter extends Filter
{
    public function apply(Request $request, $query, $value)
    {
        return $query->where('log_name', $value);
    }

    public function options(Request $request)
    {
        return Activity::distinct()->pluck('log_name', 'log_name')->toArray();
    }
}
