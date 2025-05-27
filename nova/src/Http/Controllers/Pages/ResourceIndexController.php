<?php

namespace Laravel\Nova\Http\Controllers\Pages;

use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Laravel\Nova\Http\Requests\ResourceIndexRequest;
use Laravel\Nova\Http\Resources\IndexViewResource;
use Laravel\Nova\Menu\Breadcrumb;
use Laravel\Nova\Menu\Breadcrumbs;
use Laravel\Nova\Nova;

class ResourceIndexController extends Controller
{
    /**
     * Show Resource Index page using Inertia.
     *
     * @return \Inertia\Response
     */
    public function __invoke(ResourceIndexRequest $request)
    {
        $resourceClass = IndexViewResource::make()->authorizedResourceForRequest($request);

        return Inertia::render('Nova.Index', [
            'breadcrumbs' => $this->breadcrumbs($request),
            'resourceName' => $resourceClass::uriKey(),
        ]);
    }

    /**
     * Get breadcrumb menu for the page.
     *
     * @return \Laravel\Nova\Menu\Breadcrumbs
     */
    protected function breadcrumbs(ResourceIndexRequest $request)
    {
        return Breadcrumbs::make([
            Breadcrumb::make(Nova::__('Resources')),
            Breadcrumb::resource($request->resource()),
        ]);
    }
}
