<?php

namespace App\Nova;

use Ghanem\GoogleMap\GHMap;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Actions\ApproveChanges;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class BranchChange extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\BranchChange>
     */
    public static $model = \App\Models\BranchChange::class;

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
            BelongsTo::make(__('branch'), 'branch', Branch::class)->display('name'),
            Text::make(__('name'), 'name')->sortable()
            ->hideWhenCreating()
            ->hideWhenUpdating()
            ->canSee(function ($request) {
                return !is_null($this->name);
            }),

            Text::make(__('email'), 'email')->sortable()
            ->hideWhenCreating()
            ->hideWhenUpdating()
            ->canSee(function ($request) {
                return !is_null($this->email);
            }),

            Text::make(__('mobile'), 'mobile')->sortable()
            ->hideWhenCreating()
            ->hideWhenUpdating()
            ->canSee(function ($request) {
                return !is_null($this->mobile);
            }),

            Text::make(__('imgs'), 'imgs')
            ->displayUsing(function ($value) {
                return collect(explode(',', $value))->map(function ($path) {
                    return '<a href="' . asset('public/' . $path) . '"><img src="' . asset('public/' . $path) . '" style="max-width: 300px; margin: 10px;border-radius: 10px;" /></a>';
                })->implode('');
            })
                ->asHtml()
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return !is_null($this->imgs);
                }),

            Text::make(__('tax_card'), 'tax_card')
                ->displayUsing(function ($value) {
                    return '<a href="' . asset('public/'.$value) . '"><img src="' . asset('public/'.$value) . '" style="max-width: 300px; margin: 10px;border-radius: 10px;" /></a>';
                })
                ->asHtml()
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return !is_null($this->tax_card);
                }),

            Text::make(__('commercial_register'), 'commercial_register')
            ->displayUsing(function ($value) {
                return '<a href="' . asset('public/'.$value) . '"><img src="' . asset('public/'.$value) . '" style="max-width: 300px; margin: 10px;border-radius: 10px;" /></a>';
            })
                ->asHtml()
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return !is_null($this->commercial_register);
                }),

            Text::make(__('facebook'), 'face')->sortable()
            ->hideWhenCreating()
            ->hideWhenUpdating()
            ->hideFromIndex()
            ->displayUsing(function ($value) {
                return '<a href="' . $value . '" target="_blank">' . __('facebook') . '</a>';
            })
            ->asHtml()
            ->canSee(function ($request) {
                return !is_null($this->face);
            }),

            Text::make(__('instagram'), 'insta')->sortable()
            ->hideWhenCreating()
            ->hideWhenUpdating()
            ->hideFromIndex()
            ->displayUsing(function ($value) {
                return '<a href="' . $value . '" target="_blank">' . __('instagram') . '</a>';
            })
            ->asHtml()
                ->canSee(function ($request) {
                    return !is_null($this->insta);
                }),

            Text::make(__('tiktok'), 'tiktok')->sortable()
            ->hideWhenCreating()
            ->hideWhenUpdating()
            ->hideFromIndex()
            ->displayUsing(function ($value) {
                return '<a href="' . $value . '" target="_blank">' . __('tiktok') . '</a>';
            })
            ->asHtml()
            ->canSee(function ($request) {
                return !is_null($this->tiktok);
            }),

            Text::make(__('Map Location'), 'map_location')->sortable()
            ->hideWhenCreating()
            ->hideWhenUpdating()
            ->hideFromIndex()
            ->displayUsing(function ($value) {
                return '<a href="' . $value . '" target="_blank">' . $value . '</a>';
            })
            ->asHtml(),

            Text::make(__('website'), 'website')->sortable()
            ->hideWhenCreating()
            ->hideWhenUpdating()
            ->hideFromIndex()
            ->displayUsing(function ($value) {
                return '<a href="' . $value . '" target="_blank">' . __('website') . '</a>';
            })
            ->asHtml()
            ->canSee(function ($request) {
                return !is_null($this->website);
            }),

            GHMap::make(__('location'))
                ->latitude($this->lat)
                ->longitude($this->lon)
                ->hideFromIndex()
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->canSee(function ($request) {
                    return !is_null($this->lat) && !is_null($this->lon);
                }),

            Boolean::make(__('approved'), 'is_activate')->sortable()->trueValue(1)->falseValue(0),
            Boolean::make(__('all_days'), 'all_days')
            ->sortable()->trueValue('1')
            ->falseValue('0')
            ->hideWhenCreating()
            ->hideWhenUpdating(),
            HasMany::make(__('days'), 'dayChanges', DayChange::class),
            HasMany ::make(__('payments'), 'payment_methods', BranchPaymentChange::class)
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
        return [
            new ApproveChanges,
        ];
    }

    // public function authorizedToEdit()
    // {
    //     return false;
    // }
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
