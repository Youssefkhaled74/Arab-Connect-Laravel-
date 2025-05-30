<?php

namespace Laravel\Nova\Http\Resources;

use Laravel\Nova\Http\Requests\ResourceCreateOrAttachRequest;

class ReplicateViewResource extends CreateViewResource
{
    /**
     * From Resource ID.
     *
     * @var string|int|null
     */
    protected $fromResourceId;

    /**
     * Construct a new Create View Resource.
     *
     * @param  string|int|null  $fromResourceId
     * @return void
     */
    public function __construct($fromResourceId = null)
    {
        $this->fromResourceId = $fromResourceId;
    }

    /**
     * Get current resource for the request.
     *
     * @return \Laravel\Nova\Resource
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function newResourceWith(ResourceCreateOrAttachRequest $request)
    {
        $query = $request->findModelQuery($this->fromResourceId);

        $resource = $request->resource();
        $resource::replicateQuery($request, $query);

        $resource = $request->newResourceWith($query->firstOrFail());

        $resource->authorizeToReplicate($request);

        return $resource->replicate();
    }
}
