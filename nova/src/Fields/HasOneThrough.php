<?php

namespace Laravel\Nova\Fields;

use Illuminate\Http\Request;
use Laravel\Nova\Contracts\BehavesAsPanel;
use Laravel\Nova\Contracts\RelatableField;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravel\Nova\Panel;

/**
 * @method static static make(mixed $name, string|null $attribute = null, string|null $resource = null)
 */
class HasOneThrough extends Field implements BehavesAsPanel, RelatableField
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'has-one-through-field';

    /**
     * The class name of the related resource.
     *
     * @var class-string<\Laravel\Nova\Resource>
     */
    public $resourceClass;

    /**
     * The URI key of the related resource.
     *
     * @var string
     */
    public $resourceName;

    /**
     * The displayable singular label of the relation.
     *
     * @var string
     */
    public $singularLabel;

    /**
     * The resolved HasOneThrough Resource.
     *
     * @var \Laravel\Nova\Resource|null
     */
    public $hasOneThroughResource;

    /**
     * The name of the Eloquent "has one through" relationship.
     *
     * @var string
     */
    public $hasOneThroughRelationship;

    /**
     * The key of the related Eloquent model.
     *
     * @var string|int|null
     */
    public $hasOneThroughId;

    /**
     * The callback used to determine if the HasOne field has already been filled.
     *
     * @var \Closure(\Laravel\Nova\Http\Requests\NovaRequest):bool
     */
    public $filledCallback;

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|null  $attribute
     * @param  class-string<\Laravel\Nova\Resource>|null  $resource
     * @return void
     */
    public function __construct($name, $attribute = null, $resource = null)
    {
        parent::__construct($name, $attribute);

        $resource = $resource ?? ResourceRelationshipGuesser::guessResource($name);

        $this->resourceClass = $resource;
        $this->resourceName = $resource::uriKey();
        $this->hasOneThroughRelationship = $this->attribute = $attribute ?? ResourceRelationshipGuesser::guessRelation($name);
        $this->singularLabel = $resource::singularLabel();

        $this->alreadyFilledWhen(function ($request) {
            $parentResource = Nova::resourceForKey($request->viaResource);

            if ($parentResource && $request->viaResourceId) {
                $parent = $parentResource::newModel()->find($request->viaResourceId);

                return optional($parent->{$this->attribute})->exists === true;
            }

            return false;
        });
    }

    /**
     * Get the relationship name.
     *
     * @return string
     */
    public function relationshipName()
    {
        return $this->hasOneThroughRelationship;
    }

    /**
     * Get the relationship type.
     *
     * @return string
     */
    public function relationshipType()
    {
        return 'hasOneThrough';
    }

    /**
     * Determine if the field should be displayed for the given request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return call_user_func(
            [$this->resourceClass, 'authorizedToViewAny'], $request
        ) && parent::authorize($request);
    }

    /**
     * Resolve the field's value.
     *
     * @param  mixed  $resource
     * @param  string|null  $attribute
     * @return void
     */
    public function resolve($resource, $attribute = null)
    {
        $value = null;

        if ($resource->relationLoaded($this->attribute)) {
            $value = $resource->getRelation($this->attribute);
        }

        if (! $value) {
            $value = $resource->{$this->attribute}()->withoutGlobalScopes()->getResults();
        }

        if ($value) {
            $this->alreadyFilledWhen(function () use ($value) {
                return optional($value)->exists;
            });

            $this->hasOneThroughResource = new $this->resourceClass($value);

            $this->hasOneThroughId = optional(ID::forResource($this->hasOneThroughResource))->value ?? $value->getKey();

            $this->value = $this->hasOneThroughId;
        }
    }

    /**
     * Set the displayable singular label of the resource.
     *
     * @param  string  $singularLabel
     * @return $this
     */
    public function singularLabel($singularLabel)
    {
        $this->singularLabel = $singularLabel;

        return $this;
    }

    /**
     * Make current field behaves as panel.
     *
     * @return \Laravel\Nova\Panel
     */
    public function asPanel()
    {
        return Panel::make($this->name, [$this])
            ->withMeta([
                'prefixComponent' => true,
            ])->withComponent('relationship-panel');
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return with(app(NovaRequest::class), function ($request) {
            return array_merge([
                'resourceName' => $this->resourceName,
                'hasOneThroughRelationship' => $this->hasOneThroughRelationship,
                'relationId' => $this->hasOneThroughId,
                'hasOneThroughId' => $this->hasOneThroughId,
                'authorizedToView' => optional($this->hasOneThroughResource)->authorizedToView($request) ?? true,
                'relationshipType' => $this->relationshipType(),
                'relatable' => true,
                'singularLabel' => $this->singularLabel,
                'alreadyFilled' => $this->alreadyFilled($request),
            ], parent::jsonSerialize());
        });
    }

    /**
     * Set the Closure used to determine if the HasOne field has already been filled.
     *
     * @param  \Closure(\Laravel\Nova\Http\Requests\NovaRequest):bool  $callback
     * @return $this
     */
    public function alreadyFilledWhen($callback)
    {
        $this->filledCallback = $callback;

        return $this;
    }

    /**
     * Determine if the HasOne field has alreaady been filled.
     *
     * @return bool
     */
    public function alreadyFilled(NovaRequest $request)
    {
        return call_user_func($this->filledCallback, $request) ?? false;
    }

    /**
     * Check showing on index.
     *
     * @param  mixed  $resource
     */
    public function isShownOnIndex(NovaRequest $request, $resource): bool
    {
        return false;
    }
}
