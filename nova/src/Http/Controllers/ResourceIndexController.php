<?php

namespace Laravel\Nova\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\ResourceIndexRequest;
use Laravel\Nova\Http\Resources\IndexViewResource;

class ResourceIndexController extends Controller
{
    /**
     * List the resources for administration.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ResourceIndexRequest $request)
    {
        return IndexViewResource::make()->toResponse($request);
    }
}
