<?php

namespace Laravel\Nova\Fields;

use Illuminate\Support\Arr;
use Laravel\Nova\Contracts\FilterableField;
use Laravel\Nova\Exceptions\NovaException;
use Laravel\Nova\Fields\Filters\SelectFilter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Util;
use Stringable;

/**
 * @phpstan-type TOptionLabel \Stringable|string|array{label: string, group?: string}
 * @phpstan-type TOptionValue string|int
 * @phpstan-type TOption iterable<TOptionValue, TOptionLabel>
 */
class Select extends Field implements FilterableField
{
    use FieldFilterable, Searchable, SupportsDependentFields;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'select-field';

    /**
     * The field's options callback.
     *
     * @var array<string|int, array<string, mixed>|string>|\Closure|callable|\Illuminate\Support\Collection|null
     *
     * @phpstan-var TOption|(callable(): (TOption))|(\Closure(): (TOption))|null
     */
    public $optionsCallback;

    /**
     * Set the options for the select menu.
     *
     * @param  array<string|int, array<string, mixed>|string>|\Closure|callable|\Illuminate\Support\Collection  $options
     * @return $this
     *
     * @phpstan-param TOption|(callable(): (TOption))|(\Closure(): (TOption)) $options
     */
    public function options($options)
    {
        $this->optionsCallback = $options;

        return $this;
    }

    /**
     * Display values using their corresponding specified labels.
     *
     * @return $this
     */
    public function displayUsingLabels()
    {
        $this->displayUsing(function ($value) {
            if (is_null($value) || $this->isValidNullValue($value)) {
                return $value;
            }

            return collect($this->serializeOptions(false))
                ->where('value', $value)
                ->first()['label'] ?? $value;
        });

        return $this;
    }

    /**
     * Enable subtitles within the related search results.
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function withSubtitles()
    {
        throw NovaException::helperNotSupported(__METHOD__, __CLASS__);
    }

    /**
     * Make the field filter.
     *
     * @return \Laravel\Nova\Fields\Filters\Filter
     */
    protected function makeFilter(NovaRequest $request)
    {
        return new SelectFilter($this);
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
                'options',
                'searchable',
            ]);
        });
    }

    /**
     * Serialize options for the field.
     *
     * @param  bool  $searchable
     * @return array<int, array<string, mixed>>
     *
     * @phpstan-return array<int, array{group: string, label: string, value: TOptionValue}>
     */
    protected function serializeOptions($searchable)
    {
        /** @var TOption $options */
        $options = value($this->optionsCallback);

        if (is_callable($options)) {
            $options = $options();
        }

        return collect($options ?? [])->map(function ($label, $value) use ($searchable) {
            $label = $label instanceof Stringable ? (string) $label : $label;
            $value = Util::safeInt($value);

            if ($searchable && isset($label['group'])) {
                return [
                    'label' => $label['group'].' - '.$label['label'],
                    'value' => $value,
                ];
            }

            return is_array($label) ? $label + ['value' => $value] : ['label' => $label, 'value' => $value];
        })->values()->all();
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $this->withMeta([
            'options' => $this->serializeOptions($searchable = $this->isSearchable(app(NovaRequest::class))),
        ]);

        return array_merge(parent::jsonSerialize(), [
            'searchable' => $searchable,
        ]);
    }
}
