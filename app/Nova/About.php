<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Http\Requests\NovaRequest;

class About extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\About>
     */
    public static $model = \App\Models\About::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'content';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'content',
    ];

    public static function label()
    {
        return __('Contents');
    }

    public static function singularLabel()
    {
        return __('Content');
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
            Markdown::make(__('content'),'content')->sortable(),
            Select::make(__('type'), 'type')
            ->options([
                '1' => __('what_we_offer'),
                '2' => __('why_choose_us'),
                '3' => __('about_as'),
            ])->displayUsingLabels()->sortable(),
            Text::make(__('img'), 'img')
            ->displayUsing(function ($value) {
                return '<a href="' . asset('public/admin/assets/images/abouts/'. $value) . '"><img src="' . asset('public/admin/assets/images/abouts/' . $value) . '" style="max-width: 300px; margin: 10px;border-radius: 10px;" /></a>';
            })
            // ->hideWhenCreating()
            // ->hideWhenUpdating()
            ->onlyOnDetail()
            ->asHtml(),
            Image::make(__('img'), 'img')
            ->disk('abouts')
                ->path('')
                ->creationRules('required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048')
                ->updateRules('nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048')
                ->onlyOnForms()
                ->hideFromIndex()
                ->hideFromDetail(),
            Boolean::make(__('is_activate'),'is_activate')->sortable(),
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
}
