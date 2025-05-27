<?php

namespace App\Nova\Filters;

use Laravel\Nova\Filters\DateFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ToDateFilter extends DateFilter
{
    public function name()
    {
        return __('To Date');
    }

    public function apply(Request $request, $query, $value)
    {
        if (!$value) {
            return $query;
        }

        $to = Carbon::parse($value)->endOfDay();

        return $query->where('created_at', '<=', $to);
    }
}
