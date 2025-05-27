<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Day extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Day>
     */
    public static $model = \App\Models\Day::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'day';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'day',
    ];

    public static function label()
    {
        return __('Days');
    }

    public static function singularLabel()
    {
        return __('Day');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
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
            Text::make(__('from'), 'from')->sortable()->hideWhenCreating()->hideWhenUpdating(),
            Text::make(__('to'), 'to')->sortable()->hideWhenCreating()->hideWhenUpdating(),
            Boolean::make(__('off'), 'off')
            ->sortable()->trueValue('1')
            ->falseValue('0')
            ->hideWhenCreating()
            ->hideWhenUpdating(),
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
