<?php

namespace App\Nova\Filters;

use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

namespace App\Nova\Filters;

use Laravel\Nova\Filters\Filter;
use Illuminate\Http\Request;
use App\Models\Admin;

class UserNameFilter extends Filter
{
    public function apply(Request $request, $query, $value)
    {
        return $query->whereHas('causer', function ($q) use ($value) {
            $q->where('name', $value);
        });
    }

    public function options(Request $request)
    {
        return Admin::pluck('name', 'name')->toArray();
    }
}
