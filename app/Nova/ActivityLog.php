<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\KeyValue;
use Spatie\Activitylog\Models\Activity;
use Laravel\Nova\Http\Requests\NovaRequest;



class ActivityLog extends Resource
{
    public static $model = Activity::class;
    public static $title = 'description';

    public static function label()
    {
        return __('Activity Logs');
    }

    public static function singularLabel()
    {
        return __('Activity Log');
    }
    public function fields(Request $request)
    {
        return [
            Text::make(__('log_name'), 'log_name'),
            Text::make(__('description'), 'description'),
            Text::make(__('event'), 'event'),
            Text::make(__('item_ID'), 'subject_id'),
            Text::make(__('username'), function () {
                return $this->causer ? $this->causer->name : 'N/A';
            })->sortable(),
            KeyValue::make(__('old_values'), 'properties->old')
            ->rules('json')
            ->resolveUsing(function ($value) {
                if (is_array($value)) {
                    // Format the updated_at field if it exists
                    if (isset($value['updated_at'])) {
                        $value['updated_at'] = \Carbon\Carbon::parse($value['updated_at'])->format('Y-m-d H:i:s');
                    }
                }
                return $value;
            })
            ->sortable(),
        
            KeyValue::make(__('new_values'), 'properties->attributes')
                ->rules('json')
                ->resolveUsing(function ($value) {
                    if (is_array($value)) {
                        // Format the updated_at field if it exists
                        if (isset($value['updated_at'])) {
                            $value['updated_at'] = \Carbon\Carbon::parse($value['updated_at'])->format('Y-m-d H:i:s');
                        }
                    }
                    return $value;
                })
            ->sortable(),
            Image::make(__('new_image'), function () {
                return isset($this->properties['attributes']['img']) 
                    ? asset($this->properties['attributes']['img'] )
                    : null;
            })->hideFromIndex()->disk(strtolower($this->log_name)),
            DateTime::make(__('created_at'), 'created_at')
            ->sortable()
            ->displayUsing(function ($value) {
                return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s'); // Customize the format
            }),
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
        return [
            new Filters\UserNameFilter(),
            new Filters\LogNameFilter(),
            new Filters\EventFilter(),
            new Filters\FromDateFilter(),
            new Filters\ToDateFilter(),
        ];
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

    public function authorizedToDelete(Request $request)
    {
        return false;
    }

}
