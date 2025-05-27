<?php

namespace App\Nova\Filters;

use Laravel\Nova\Filters\Filter;
use Illuminate\Http\Request;

class EventFilter extends Filter
{
    public function apply(Request $request, $query, $value)
    {
        return $query->where('event', $value);
    }

    public function options(Request $request)
    {
        return [
            'Created' => 'created',
            'Updated' => 'updated',
            'Deleted' => 'deleted',
        ];
    }
}
