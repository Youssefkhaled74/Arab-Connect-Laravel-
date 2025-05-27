<?php

namespace Laravel\Nova\Metrics;

use Illuminate\Support\Collection;
use Laravel\Nova\FilterDecoder;
use Laravel\Nova\Http\Requests\NovaRequest;

trait ResolvesFilters
{
    /**
     * Filters for the metric.
     *
     * @var \Illuminate\Support\Collection|null
     */
    protected $filters;

    /**
     * Set filters for current metric.
     *
     * @return $this
     */
    public function setAvailableFilters(Collection $filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * Apply filter query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function applyFilterQuery(NovaRequest $request, $query)
    {
        if ($this->filters instanceof Collection) {
            (new FilterDecoder($request->filter, $this->filters))
                ->filters()
                ->each
                ->__invoke($request, $query);
        }

        return $query;
    }
}
