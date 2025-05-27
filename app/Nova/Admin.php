<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\Boolean;
use Illuminate\Support\Facades\Hash;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class Admin extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Admin>
     */
    public static $model = \App\Models\Admin::class;

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
        return __('Admins');
    }

    public static function singularLabel()
    {
        return __('Admin');
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

            Text::make('name')
                ->rules('required', 'string', 'max:255'),
            
            Text::make('email')
            ->rules('required', 'string', 'email', 'max:255', Rule::unique('admins', 'email')->ignore(request()->route('resourceId'))),
            
            Text::make('phone')
                ->rules('required', 'string', Rule::unique('admins', 'phone')->ignore(request()->route('resourceId'))),
            
                Text::make('password')
                ->onlyOnForms()
                ->rules('required', 'string', 'min:8')
                ->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
                    if ($request->filled($requestAttribute)) {
                        $model->{$attribute} = Hash::make($request->input($requestAttribute));
                    }
                }),
            
            Boolean::make('is_activate')
                ->rules('required', 'boolean')
                ->trueValue(1)
                ->falseValue(0),
            
            MorphToMany::make('Roles', 'roles', \Sereny\NovaPermissions\Nova\Role::class),
            
            MorphToMany::make('Permissions', 'permissions', \Sereny\NovaPermissions\Nova\Permission::class),
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
