<?php

namespace Laravel\Nova\Fields;

use Illuminate\Database\Eloquent\Relations\Relation;
use Laravel\Nova\Http\Requests\NovaRequest;

trait ResolvesReverseRelation
{
    /**
     * The reverse relation for the related resource.
     *
     * @var string|null
     */
    public $reverseRelation;

    /**
     * Determine if the field is the reverse relation of a showed index view.
     *
     * @return bool
     */
    public function isReverseRelation(NovaRequest $request)
    {
        if (! $request->viaResource || ($this->resourceName && $this->resourceName !== $request->viaResource)) {
            return false;
        }

        $reverse = $this->getReverseRelation($request);

        return $reverse === $request->viaRelationship;
    }

    /**
     * Get reverse relation field name.
     *
     * @return string
     */
    public function getReverseRelation(NovaRequest $request)
    {
        if (is_null($this->reverseRelation)) {
            $viaModel = forward_static_call(
                [$resourceClass = $request->viaResource(), 'newModel']
            );

            $viaResource = new $resourceClass($viaModel);

            $resource = $request->newResource();

            $this->reverseRelation = $viaResource->availableFields($request)
                ->first(function ($field) use ($viaModel, $resource) {
                    if (! isset($field->resourceName) || $field->resourceName !== $resource::uriKey()) {
                        return false;
                    }

                    if (! $field instanceof MorphMany
                        && ! $field instanceof HasMany
                        && ! $field instanceof HasOne) {
                        return false;
                    }

                    if ($field instanceof HasOne && $field->ofManyRelationship()) {
                        return false;
                    }

                    $model = $resource->model();

                    if (! method_exists($viaModel, $field->attribute) || ! method_exists($model, $this->attribute)) {
                        return false;
                    }

                    $relation = $viaModel->{$field->attribute}();

                    return $this->getRelationForeignKeyName($relation) === $this->getRelationForeignKeyName(
                        $resource->model()->{$this->attribute}()
                    );
                })->attribute ?? '';
        }

        return $this->reverseRelation;
    }

    /**
     * Get foreign key name for relation.
     *
     * @return string
     */
    protected function getRelationForeignKeyName(Relation $relation)
    {
        return method_exists($relation, 'getForeignKeyName')
            ? $relation->getForeignKeyName()
            : $relation->getForeignKey();
    }
}
