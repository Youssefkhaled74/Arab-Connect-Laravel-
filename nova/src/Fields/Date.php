<?php

namespace Laravel\Nova\Fields;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use DateTimeInterface;
use Exception;
use Illuminate\Support\Arr;
use Laravel\Nova\Contracts\FilterableField;
use Laravel\Nova\Fields\Filters\DateFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class Date extends Field implements FilterableField
{
    use FieldFilterable, SupportsDependentFields;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'date-field';

    /**
     * The minimum value that can be assigned to the field.
     *
     * @var string|null
     */
    public $min;

    /**
     * The maximum value that can be assigned to the field.
     *
     * @var string|null
     */
    public $max;

    /**
     * The step size the field will increment and decrement by.
     *
     * @var string|int|null
     */
    public $step;

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|\Closure|callable|object|null  $attribute
     * @param  (callable(mixed, mixed, ?string):(mixed))|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, ?callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback ?? function ($value) {
            if (! is_null($value)) {
                if ($value instanceof DateTimeInterface) {
                    return $value instanceof CarbonInterface
                                ? $value->toDateString()
                                : $value->format('Y-m-d');
                }

                throw new Exception("Date field must cast to 'date' in Eloquent model.");
            }
        });
    }

    /**
     * The minimum value that can be assigned to the field.
     *
     * @param  \Carbon\CarbonInterface|string  $min
     * @return $this
     */
    public function min($min)
    {
        if (is_string($min)) {
            $min = Carbon::parse($min);
        }

        $this->min = $min->toDateString();

        return $this;
    }

    /**
     * The maximum value that can be assigned to the field.
     *
     * @param  \Carbon\CarbonInterface|string  $max
     * @return $this
     */
    public function max($max)
    {
        if (is_string($max)) {
            $max = Carbon::parse($max);
        }

        $this->max = $max->toDateString();

        return $this;
    }

    /**
     * The step size the field will increment and decrement by.
     *
     * @param  string|int|\Carbon\CarbonInterval  $step
     * @return $this
     */
    public function step($step)
    {
        $this->step = $step instanceof CarbonInterval ? $step->totalDays : $step;

        return $this;
    }

    /**
     * Resolve the default value for the field.
     *
     * @return string|null
     */
    public function resolveDefaultValue(NovaRequest $request)
    {
        /** @var \DateTimeInterface|string|null $value */
        $value = parent::resolveDefaultValue($request);

        if ($value instanceof DateTimeInterface) {
            return $value instanceof CarbonInterface
                        ? $value->toDateString()
                        : $value->format('Y-m-d');
        }

        return $value;
    }

    /**
     * Make the field filter.
     *
     * @return \Laravel\Nova\Fields\Filters\Filter
     */
    protected function makeFilter(NovaRequest $request)
    {
        return new DateFilter($this);
    }

    /**
     * Define the default filterable callback.
     *
     * @return callable(\Laravel\Nova\Http\Requests\NovaRequest, \Illuminate\Database\Eloquent\Builder, mixed, string):\Illuminate\Database\Eloquent\Builder
     */
    protected function defaultFilterableCallback()
    {
        return function (NovaRequest $request, $query, $value, $attribute) {
            [$min, $max] = $value;

            if (! is_null($min) && ! is_null($max)) {
                return $query->whereBetween($attribute, [$min, $max]);
            } elseif (! is_null($min)) {
                return $query->where($attribute, '>=', $min);
            }

            return $query->where($attribute, '<=', $max);
        };
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array
     */
    public function serializeForFilter()
    {
        return transform($this->jsonSerialize(), function ($field) {
            return Arr::only($field, [
                'uniqueKey',
                'name',
                'attribute',
                'type',
                'placeholder',
                'extraAttributes',
            ]);
        });
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), array_filter([
            'min' => $this->min,
            'max' => $this->max,
            'step' => $this->step ?? 'any',
        ]));
    }
}
