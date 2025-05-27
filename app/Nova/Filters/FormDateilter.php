<?php
namespace App\Nova\Filters;

use Laravel\Nova\Filters\DateFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FromDateFilter extends DateFilter
{
    public function name()
    {
        return __('From Date');
    }

    public function apply(Request $request, $query, $value)
    {
        if (!$value) {
            return $query;
        }

        $from = Carbon::parse($value)->startOfDay();

        return $query->where('created_at', '>=', $from);
    }
}
