<?php

namespace App\Nova;

use App\Nova\Day;
use App\Nova\Owner;
use App\Nova\Category;
use App\Nova\Payments;
use Ghanem\GoogleMap\GHMap;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Actions\ApproveChanges;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;



class Branch extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Branch>
     */
    public static $model = \App\Models\Branch::class;

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
        return __('Branches');
    }

    public static function singularLabel()
    {
        return __('Branch');
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
            Text::make(__('name'), 'name')->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            Text::make(__('email'), 'email')->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            Text::make(__('mobile'), 'mobile')->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            Text::make(__('imgs'), 'imgs')
                ->displayUsing(function ($value) {
                    return collect(explode(',', $value))->map(function ($path) {
                        return '<a href="' . asset('public/' . $path) . '"><img src="' . asset('public/' . $path) . '" style="max-width: 300px; margin: 10px;border-radius: 10px;" /></a>';
                    })->implode('');
                })
                ->asHtml()
                ->onlyOnDetail(),

            Text::make(__('tax_card'), 'tax_card')
                ->displayUsing(function ($value) {
                    return '<a href="' . asset('public/' . $value) . '"><img src="' . asset('public/' . $value) . '" style="max-width: 300px; margin: 10px;border-radius: 10px;" /></a>';
                })
                ->asHtml()
                ->onlyOnDetail(),

            Text::make(__('commercial_register'), 'commercial_register')
                ->displayUsing(function ($value) {
                    return '<a href="' . asset('public/' . $value) . '"><img src="' . asset('public/' . $value) . '" style="max-width: 300px; margin: 10px;border-radius: 10px;" /></a>';
                })
                ->asHtml()
                ->onlyOnDetail(),

            Text::make(__('facebook'), 'face')->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->hideFromIndex()
                ->displayUsing(function ($value) {
                    return '<a href="' . $value . '" target="_blank">' . __('facebook') . '</a>';
                })
                ->asHtml(),

            Text::make(__('instagram'), 'insta')->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->hideFromIndex()
                ->displayUsing(function ($value) {
                    return '<a href="' . $value . '" target="_blank">' . __('instagram') . '</a>';
                })
                ->asHtml(),

            Text::make(__('tiktok'), 'tiktok')->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->hideFromIndex()
                ->displayUsing(function ($value) {
                    return '<a href="' . $value . '" target="_blank">' . __('tiktok') . '</a>';
                })
                ->asHtml(),

            Text::make(__('website'), 'website')->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->hideFromIndex()
                ->displayUsing(function ($value) {
                    return '<a href="' . $value . '" target="_blank">' . __('website') . '</a>';
                })
                ->asHtml(),

            Text::make(__('Map Location'), 'map_location')->sortable()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->hideFromIndex()
                ->displayUsing(function ($value) {
                    return '<a href="' . $value . '" target="_blank">' . $value . '</a>';
                })
                ->asHtml(),

            BelongsTo::make(__('Sub Category'), 'subCategory', \App\Nova\SubCategory::class)
                ->display('name')
                ->nullable(),

            BelongsTo::make(__('owner'), 'owner', User::class)->display('name')
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            GHMap::make(__('location'))
                ->latitude($this->lat)
                ->longitude($this->lon)
                ->hideFromIndex()
                ->hideWhenCreating()
                ->hideWhenUpdating(),
            // Boolean::make(__('is_activate'), 'is_activate')->sortable()->trueValue(1)->falseValue(0),
            Boolean::make(__('is_published'), 'is_published')->sortable()->trueValue(1)->falseValue(0),
            Boolean::make(__('is_verified'), 'is_verified')->sortable()->trueValue(1)->falseValue(0),
            Boolean::make(__('all_days'), 'all_days')
                ->sortable()->trueValue('1')
                ->falseValue('0')
                ->hideWhenCreating()
                ->hideWhenUpdating(),
            HasMany::make(__('days'), 'days', Day::class),
            BelongsToMany::make(__('payments'), 'payments', PaymentMethod::class)
                ->hideWhenCreating()->hideWhenUpdating(),
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

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToReplicate(Request $request)
    {
        return false;
    }
}
