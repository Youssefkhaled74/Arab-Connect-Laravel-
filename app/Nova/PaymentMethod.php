<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Http\Requests\NovaRequest;

class PaymentMethod extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\PaymentMethod>
     */
    public static $model = \App\Models\PaymentMethod::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
    ];

    public static function label()
     {
         return __('Payment Methods');
     }
 
     public static function singularLabel()
     {
         return __('Payment Method');
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
            Text::make(__('name'),'name')->sortable(),
            Text::make(__('img'), 'img')
            ->displayUsing(function ($value) {
                return '<a href="' . asset('public/admin/assets/images/payment/' . $value) . '"><img src="' . asset('public/admin/assets/images/payment/' . $value) . '" style="max-width: 300px; margin: 10px;border-radius: 10px;" /></a>';
            })
                // ->hideWhenCreating()
                // ->hideWhenUpdating()
                ->onlyOnDetail()
                ->asHtml(),
            Image::make(__('img'), 'img')
            ->disk('payment')
                ->path('')
                ->creationRules('required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048')
                ->updateRules('nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048')
                ->onlyOnForms()
                ->hideFromIndex()
                ->hideFromDetail(),
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


    public function authorizedToReplicate(Request $request)
    {
        return false;
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }
}
