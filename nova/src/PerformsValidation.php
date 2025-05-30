<?php

namespace Laravel\Nova;

use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Contracts\PivotableField;
use Laravel\Nova\Http\Requests\NovaRequest;

/**
 * @phpstan-import-type TFieldValidationRules from \Laravel\Nova\Fields\Field
 */
trait PerformsValidation
{
    /**
     * Validate a resource creation request.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function validateForCreation(NovaRequest $request)
    {
        static::validatorForCreation($request)
            ->addCustomAttributes(self::attributeNamesForFields($request)->toArray())
            ->validate();
    }

    /**
     * Create a validator instance for a resource creation request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function validatorForCreation(NovaRequest $request)
    {
        return Validator::make($request->all(), static::rulesForCreation($request))
            ->after(function ($validator) use ($request) {
                static::afterValidation($request, $validator);
                static::afterCreationValidation($request, $validator);
            });
    }

    /**
     * Get the validation rules for a resource creation request.
     *
     * @return array<array-key, mixed>
     *
     * @phpstan-return array<array-key, TFieldValidationRules>
     */
    public static function rulesForCreation(NovaRequest $request)
    {
        return static::formatRules($request, self::newResource()
            ->creationFields($request)
            ->applyDependsOn($request)
            ->withoutReadonly($request)
            ->withoutUnfillable()
            ->mapWithKeys(function ($field) use ($request) {
                return $field->getCreationRules($request);
            })->all());
    }

    /**
     * Get the creation validation rules for a specific field.
     *
     * @param  string  $field
     * @return array<array-key, mixed>
     *
     * @phpstan-return array<array-key, TFieldValidationRules>
     */
    public static function creationRulesFor(NovaRequest $request, $field)
    {
        return static::formatRules($request, self::newResource()
            ->availableFields($request)
            ->where('attribute', $field)
            ->applyDependsOn($request)
            ->withoutUnfillable()
            ->mapWithKeys(function ($field) use ($request) {
                return $field->getCreationRules($request);
            })->all());
    }

    /**
     * Validate a resource update request.
     *
     * @param  \Laravel\Nova\Resource|null  $resource
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function validateForUpdate(NovaRequest $request, $resource = null)
    {
        static::validatorForUpdate($request, $resource)
            ->addCustomAttributes(self::attributeNamesForFields($request, $resource)->toArray())
            ->validate();
    }

    /**
     * Create a validator instance for a resource update request.
     *
     * @param  \Laravel\Nova\Resource|null  $resource
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function validatorForUpdate(NovaRequest $request, $resource = null)
    {
        return Validator::make($request->all(), static::rulesForUpdate($request, $resource))
            ->after(function ($validator) use ($request) {
                static::afterValidation($request, $validator);
                static::afterUpdateValidation($request, $validator);
            });
    }

    /**
     * Get the validation rules for a resource update request.
     *
     * @param  \Laravel\Nova\Resource|null  $resource
     * @return array<array-key, mixed>
     *
     * @phpstan-return array<array-key, TFieldValidationRules>
     */
    public static function rulesForUpdate(NovaRequest $request, $resource = null)
    {
        $resource = $resource ?? self::newResource();

        return static::formatRules($request, $resource->updateFields($request)
            ->applyDependsOn($request)
            ->withoutReadonly($request)
            ->withoutUnfillable()
            ->mapWithKeys(function ($field) use ($request) {
                return $field->getUpdateRules($request);
            })->all());
    }

    /**
     * Get the update validation rules for a specific field.
     *
     * @param  string  $field
     * @return array<array-key, mixed>
     *
     * @phpstan-return array<array-key, TFieldValidationRules>
     */
    public static function updateRulesFor(NovaRequest $request, $field)
    {
        return static::formatRules($request, self::newResource()
            ->availableFields($request)
            ->where('attribute', $field)
            ->applyDependsOn($request)
            ->withoutUnfillable()
            ->mapWithKeys(function ($field) use ($request) {
                return $field->getUpdateRules($request);
            })->all());
    }

    /**
     * Validate a resource attachment request.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function validateForAttachment(NovaRequest $request)
    {
        static::validatorForAttachment($request)->validate();
    }

    /**
     * Create a validator instance for a resource attachment request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function validatorForAttachment(NovaRequest $request)
    {
        return Validator::make($request->all(), static::rulesForAttachment($request));
    }

    /**
     * Get the validation rules for a resource attachment request.
     *
     * @return array<array-key, mixed>
     *
     * @phpstan-return array<array-key, TFieldValidationRules>
     */
    public static function rulesForAttachment(NovaRequest $request)
    {
        return static::formatRules($request, self::newResource()
            ->creationPivotFields($request, $request->relatedResource)
            ->mapWithKeys(function ($field) use ($request) {
                return $field->getCreationRules($request);
            })->all());
    }

    /**
     * Validate a resource attachment update request.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function validateForAttachmentUpdate(NovaRequest $request)
    {
        static::validatorForAttachmentUpdate($request)->validate();
    }

    /**
     * Create a validator instance for a resource attachment update request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function validatorForAttachmentUpdate(NovaRequest $request)
    {
        return Validator::make($request->all(), static::rulesForAttachmentUpdate($request));
    }

    /**
     * Get the validation rules for a resource attachment update request.
     *
     * @return array<array-key, mixed>
     *
     * @phpstan-return array<array-key, TFieldValidationRules>
     */
    public static function rulesForAttachmentUpdate(NovaRequest $request)
    {
        return static::formatRules($request, self::newResource()
            ->updatePivotFields($request, $request->relatedResource)
            ->mapWithKeys(function ($field) use ($request) {
                return $field->getUpdateRules($request);
            })->all());
    }

    /**
     * Perform any final formatting of the given validation rules.
     *
     * @return array<array-key, mixed>
     *
     * @phpstan-return array<array-key, TFieldValidationRules>
     */
    protected static function formatRules(NovaRequest $request, array $rules)
    {
        $replacements = array_filter([
            '{{resourceId}}' => str_replace(['\'', '"', ',', '\\'], '', $request->resourceId ?? ''),
        ]);

        if (empty($replacements)) {
            return $rules;
        }

        return collect($rules)->map(function ($rules) use ($replacements) {
            return collect($rules)->map(function ($rule) use ($replacements) {
                return is_string($rule)
                            ? str_replace(array_keys($replacements), array_values($replacements), $rule)
                            : $rule;
            })->all();
        })->all();
    }

    /**
     * Get the validation attribute for a specific field.
     *
     * @param  string  $field
     * @return string
     */
    public static function validationAttributeFor(NovaRequest $request, $field)
    {
        return self::newResource()
            ->availableFields($request)
            ->filter(function ($field) {
                return ! $field instanceof PivotableField;
            })
            ->firstWhere('resourceName', $field)
            ->getValidationAttribute($request);
    }

    /**
     * Get the validation attachable attribute for a specific field.
     *
     * @param  string  $field
     * @return string
     */
    public static function validationAttachableAttributeFor(NovaRequest $request, $field)
    {
        return self::newResource()
            ->availableFields($request)
            ->filter(function ($field) {
                return $field instanceof PivotableField;
            })
            ->firstWhere('resourceName', $field)
            ->getValidationAttribute($request);
    }

    /**
     * Map field attributes to field names.
     *
     * @param  \Laravel\Nova\Resource|null  $resource
     * @return \Illuminate\Support\Collection<string, string>
     */
    private static function attributeNamesForFields(NovaRequest $request, $resource = null)
    {
        $resource = $resource ?: self::newResource();

        return $resource
            ->availableFields($request)
            ->reject(function ($field) {
                return empty($field->name);
            })
            ->mapWithKeys(function ($field) use ($request) {
                return $field->getValidationAttributeNames($request);
            });
    }

    /**
     * Handle any post-validation processing.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    protected static function afterValidation(NovaRequest $request, $validator)
    {
        //
    }

    /**
     * Handle any post-creation validation processing.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     */
    protected static function afterCreationValidation(NovaRequest $request, $validator)
    {
        //
    }

    /**
     * Handle any post-update validation processing.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     */
    protected static function afterUpdateValidation(NovaRequest $request, $validator)
    {
        //
    }
}
