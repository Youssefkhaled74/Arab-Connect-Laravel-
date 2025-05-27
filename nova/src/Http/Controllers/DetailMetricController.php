<?php

namespace Laravel\Nova\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\MetricRequest;

class DetailMetricController extends Controller
{
    /**
     * Get the specified metric's value.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(MetricRequest $request)
    {
        return response()->json([
            'value' => $request->detailMetric()->resolve($request),
        ]);
    }
}
