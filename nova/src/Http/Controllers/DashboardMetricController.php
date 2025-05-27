<?php

namespace Laravel\Nova\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\DashboardMetricRequest;

class DashboardMetricController extends Controller
{
    /**
     * Get the specified metric's value.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(DashboardMetricRequest $request)
    {
        return response()->json([
            'value' => $request->metric()->resolve($request),
        ]);
    }
}
