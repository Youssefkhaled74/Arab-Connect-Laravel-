<?php

namespace Laravel\Nova\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\MetricRequest;

class MetricController extends Controller
{
    /**
     * List the metrics for the given resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(MetricRequest $request)
    {
        return response()->json(
            $request->availableMetrics()
        );
    }

    /**
     * Get the specified metric's value.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(MetricRequest $request)
    {
        return response()->json([
            'value' => $request->metric()->resolve($request),
        ]);
    }
}
