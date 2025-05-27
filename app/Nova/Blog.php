<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class Blog extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Blog>
     */
    public static $model = \App\Models\Blog::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title',
    ];

    public static function label()
    {
        return __('Blogs');
    }

    public static function singularLabel()
    {
        return __('Blog');
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
            Text::make(__('title'), 'title')->sortable(),
            Text::make(__('slug'), 'slug')->sortable(),
            Markdown::make(__('description'), 'description')->sortable(),
            BelongsTo::make(__('category'), 'category', Category::class),
            Text::make(__('meta_title'), 'meta_title')->sortable(),
            Text::make(__('meta_description'), 'meta_description')->sortable(),
            Text::make(__('meta_tags'), 'meta_tags')->sortable(),
            Text::make(__('meta_keywords'), 'meta_keywords')->sortable(),
            Text::make(__('img'), 'imgs')
            ->displayUsing(function ($value) {
                return '<a href="' . asset('public/admin/assets/images/blogs/' . $value) . '"><img src="' . asset('public/admin/assets/images/blogs/' . $value) . '" style="max-width: 300px; margin: 10px;border-radius: 10px;" /></a>';
            })
            // ->hideWhenCreating()
            // ->hideWhenUpdating()
            ->onlyOnDetail()
            ->asHtml(),
            Image::make(__('img'), 'imgs')
            ->disk('blogs')
                ->path('')
                ->creationRules('required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048')
                ->updateRules('nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048')
                ->onlyOnForms()
                ->hideFromIndex()
                ->hideFromDetail(),
            Boolean::make(__('is_activate'), 'is_activate')->sortable(),

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
