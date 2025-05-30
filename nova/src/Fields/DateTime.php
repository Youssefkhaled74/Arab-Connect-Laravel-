<?php

namespace Laravel\Nova\Fields;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use DateTimeInterface;
use Exception;
use Illuminate\Support\Arr;
use Laravel\Nova\Contracts\FilterableField;
use Laravel\Nova\Fields\Filters\DateTimeFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class DateTime extends Field implements FilterableField
{
    use FieldFilterable, SupportsDependentFields;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'date-time-field';

    /**
     * The original raw value of the field.
     *
     * @var string
     */
    public $originalValue;

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
     * @var int|null
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
        parent::__construct($name, $attribute, $resolveCallback ?? function ($value, $request) {
            if (! is_null($value)) {
                if ($value instanceof DateTimeInterface) {
                    return $value instanceof CarbonInterface
                        ? $value->toIso8601String()
                        : $value->format(DateTimeInterface::ATOM);
                }

                throw new Exception("DateTime field must cast to 'datetime' in Eloquent model.");
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

        $this->min = $min->toDateTimeLocalString();

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

        $this->max = $max->toDateTimeLocalString();

        return $this;
    }

    /**
     * The step size the field will increment and decrement by.
     *
     * @param  int|\Carbon\CarbonInterval  $step
     * @return $this
     */
    public function step($step)
    {
        $this->step = $step instanceof CarbonInterval ? $step->totalSeconds : $step;

        return $this;
    }

    /**
     * Resolve the default value for the field.
     *
     * @return string
     */
    public function resolveDefaultValue(NovaRequest $request)
    {
        $value = parent::resolveDefaultValue($request);

        if ($value instanceof DateTimeInterface) {
            return $value instanceof CarbonInterface
                ? $value->toIso8601String()
                : $value->format(DateTimeInterface::ATOM);
        }

        return $value;
    }

    /**
     * Resolve the field's value using the display callback.
     *
     * @param  mixed  $value
     * @param  mixed  $resource
     * @param  string  $attribute
     * @return void
     */
    protected function resolveUsingDisplayCallback($value, $resource, $attribute)
    {
        $this->usesCustomizedDisplay = true;

        if ($value instanceof DateTimeInterface) {
            $this->value = $value instanceof CarbonInterface
                ? $value->toIso8601String()
                : $value->format(DateTimeInterface::ATOM);
        }

        $this->originalValue = $this->value;
        $this->displayedAs = call_user_func($this->displayCallback, $value, $resource, $attribute);
    }

    /**
     * Make the field filter.
     *
     * @return \Laravel\Nova\Fields\Filters\Filter
     */
    protected function makeFilter(NovaRequest $request)
    {
        return new DateTimeFilter($this);
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
     * Prepare the field for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return array_merge([
            'originalValue' => $this->originalValue,
        ], array_filter([
            'min' => $this->min,
            'max' => $this->max,
            'step' => $this->step ?? 1,
        ]), parent::jsonSerialize());
    }
}
