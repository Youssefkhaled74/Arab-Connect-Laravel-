<?php

namespace Laravel\Nova\Fields\Repeater\Presets;

use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Fields\Repeater\RepeatableCollection;
use Laravel\Nova\Http\Requests\NovaRequest;

interface Preset
{
    /**
     * Save the field value to permanent storage.
     *
     * @param  string|null  $uniqueField
     * @return \Closure|void
     */
    public function set(NovaRequest $request, Model $model, string $attribute, RepeatableCollection $repeatables, $uniqueField);

    /**
     * Retrieve the value from storage and hydrate the field's value.
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(NovaRequest $request, Model $model, string $attribute, RepeatableCollection $repeatables);
}
