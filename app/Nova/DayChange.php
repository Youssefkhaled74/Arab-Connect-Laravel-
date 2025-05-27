<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\DayChange as DayChangeResource;
use Illuminate\Http\Request;

class DayChange extends Resource
{
    public static $model = DayChangeResource::class;

    public static $title = 'day';

    public static $search = [
        'day'
    ];

    public static function label()
    {
        return __('Day Changes');
    }

    public static function singularLabel()
    {
        return __('Day Change');
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Select::make(__('day'), 'day')
            ->options([
                '1' => __('friday'),
                '2' => __('saturday'),
                '3' => __('sunday'),
                '4' => __('monday'),
                '5' => __('tuesday'),
                '6' => __('wednesday'),
                '7' => __('thursday'),
            ])
            ->displayUsingLabels()
            ->hideWhenCreating()
            ->hideWhenUpdating(),
            Text::make(__('from'), 'from'),
            Text::make(__('to'), 'to'),
            Boolean::make(__('off'), 'off'),
        ];
    }
    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }

    public function authorizedToUpdate(Request $request)
    {
        return false;
    }

    public function authorizedToReplicate(Request $request)
    {
        return false;
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }
}
