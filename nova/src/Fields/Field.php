<?php

namespace Laravel\Nova\Fields;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Traits\Tappable;
use JsonSerializable;
use Laravel\Nova\Contracts\Resolvable;
use Laravel\Nova\Exceptions\NovaException;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\HasHelpText;
use Laravel\Nova\Util;

/**
 * @phpstan-type TFieldValidationRules \Stringable|string|\Illuminate\Contracts\Validation\ValidationRule|\Illuminate\Contracts\Validation\Rule|\Illuminate\Contracts\Validation\InvokableRule|callable
 * @phpstan-type TValidationRules array<int, TFieldValidationRules>|\Stringable|string|(callable(string, mixed, \Closure):(void))
 *
 * @method static static make(mixed $name, string|\Closure|callable|object|null $attribute = null, callable|null $resolveCallback = null)
 */
#[\AllowDynamicProperties]
abstract class Field extends FieldElement implements JsonSerializable, Resolvable
{
    use DependentFields;
    use HandlesValidation;
    use HasHelpText;
    use Macroable;
    use PeekableFields;
    use PreviewableFields;
    use SupportsFullWidthFields;
    use Tappable;

    const LEFT_ALIGN = 'left';

    const CENTER_ALIGN = 'center';

    const RIGHT_ALIGN = 'right';

    /**
     * The displayable name of the field.
     *
     * @var string
     */
    public $name;

    /**
     * The attribute / column name of the field.
     *
     * @var string
     */
    public $attribute;

    /**
     * The field's resolved value.
     *
     * @var mixed
     */
    public $value;

    /**
     * The value displayed to the user.
     *
     * @var string|null
     */
    public $displayedAs;

    /**
     * The callback to be used to resolve the field's display value.
     *
     * @var (callable(mixed, mixed, string):(mixed))|null
     */
    public $displayCallback;

    /**
     * Indicates whether the display value has been customized by the user.
     *
     * @var bool
     */
    public $usesCustomizedDisplay = false;

    /**
     * The callback to be used to resolve the field's value.
     *
     * @var (callable(mixed, mixed, ?string):(mixed))|null
     */
    public $resolveCallback;

    /**
     * The callback to be used to hydrate the model attribute.
     *
     * @var (callable(\Laravel\Nova\Http\Requests\NovaRequest, \Illuminate\Database\Eloquent\Model|\Laravel\Nova\Support\Fluent, string, string):(mixed))|null
     */
    public $fillCallback;

    /**
     * The callback to be used for computed field.
     *
     * @var (\Closure(mixed):(mixed))|(callable(mixed):(mixed))|null
     */
    protected $computedCallback;

    /**
     * The callback to be used for the field's default value.
     *
     * @var (\Closure(\Laravel\Nova\Http\Requests\NovaRequest):(mixed))|null
     */
    protected $defaultCallback;

    /**
     * Indicates if the field should be sortable.
     *
     * @var bool
     */
    public $sortable = false;

    /**
     * Indicates if the field is nullable.
     *
     * @var bool
     */
    public $nullable = false;

    /**
     * Values which will be replaced to null.
     *
     * @var array<int, mixed>
     */
    public $nullValues = [''];

    /**
     * Indicates if the field was resolved as a pivot field.
     *
     * @var bool
     */
    public $pivot = false;

    /**
     * The accessor that should be used to refer as a pivot field.
     *
     * @var string|null
     */
    public $pivotAccessor;

    /**
     * The text alignment for the field's text in tables.
     *
     * @var string
     */
    public $textAlign = 'left';

    /**
     * Indicates if the field should allow its whitespace to be wrapped.
     *
     * @var bool
     */
    public $wrapping = false;

    /**
     * Indicates if the field label and form element should sit on top of each other.
     *
     * @var bool
     */
    public $stacked = false;

    /**
     * The custom components registered for fields.
     *
     * @var array<class-string<\Laravel\Nova\Fields\Field>, string>
     */
    public static $customComponents = [];

    /**
     * The callback used to determine if the field is readonly.
     *
     * @var (callable(\Laravel\Nova\Http\Requests\NovaRequest):(bool))|bool|null
     */
    public $readonlyCallback;

    /**
     * The callback used to determine if the field is required.
     *
     * @var (callable(\Laravel\Nova\Http\Requests\NovaRequest):(bool))|bool|null
     */
    public $requiredCallback;

    /**
     * The resource associated with the field.
     *
     * @var mixed
     */
    public $resource;

    /**
     * Indicates whether the field is visible.
     *
     * @var bool
     */
    public $visible = true;

    /**
     * The placeholder for the field.
     *
     * @var string|null
     */
    public $placeholder;

    /**
     * Indicated whether the field should show its label.
     *
     * @var bool
     */
    public $withLabel = true;

    /**
     * Indicated whether the field should display as though it is inline.
     *
     * @var bool
     */
    public $inline = false;

    /**
     * Indicated whether the field should display as though it is compact.
     *
     * @var bool
     */
    public $compact = false;

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|\Closure|callable|object|null  $attribute
     * @param  (callable(mixed, mixed, ?string):(mixed))|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, ?callable $resolveCallback = null)
    {
        $this->name = $name;
        $this->resolveCallback = $resolveCallback;

        $this->default(null);

        if ($attribute instanceof Closure || (is_callable($attribute) && is_object($attribute))) {
            $this->computedCallback = $attribute;
            $this->attribute = 'ComputedField';
        } else {
            $this->attribute = $attribute ?? str_replace(' ', '_', Str::lower($name));
        }
    }

    /**
     * Set the value for the field.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Stack the label above the field.
     *
     * @return $this
     */
    public function stacked()
    {
        $this->stacked = true;

        return $this;
    }

    /**
     * Resolve the field's value for display.
     *
     * @param  mixed  $resource
     * @param  string|null  $attribute
     * @return void
     */
    public function resolveForDisplay($resource, $attribute = null)
    {
        $this->resource = $resource;

        $attribute = $attribute ?? $this->attribute;

        if (! $this->displayCallback) {
            $this->resolve($resource, $attribute);
        } elseif (is_callable($this->displayCallback)) {
            if ($attribute === 'ComputedField') {
                $this->value = call_user_func($this->computedCallback, $resource);
            }

            tap($this->value ?? $this->resolveAttribute($resource, $attribute), function ($value) use (
                $resource,
                $attribute
            ) {
                $this->value = $value;
                $this->resolveUsingDisplayCallback($value, $resource, $attribute);
            });
        }
    }

    /**
     * Resolve the field's value using the display callback.
     *
     * @param  mixed  $value
     * @param  mixed  $resource
     * @param  string  $attribute
     * @return void
     */
    protected function resolveUsingDisplayCallback($value, $resource, $attribute)
    {
        $this->usesCustomizedDisplay = true;
        $this->displayedAs = call_user_func($this->displayCallback, $value, $resource, $attribute);
    }

    /**
     * Resolve the field's value.
     *
     * @param  mixed  $resource
     * @param  string|null  $attribute
     * @return void
     */
    public function resolve($resource, $attribute = null)
    {
        $this->resource = $resource;

        $attribute = $attribute ?? $this->attribute;

        if ($attribute === 'ComputedField') {
            $this->value = call_user_func($this->computedCallback, $resource);

            return;
        }

        if (! $this->resolveCallback) {
            $this->value = $this->resolveAttribute($resource, $attribute);
        } elseif (is_callable($this->resolveCallback)) {
            tap($this->resolveAttribute($resource, $attribute), function ($value) use ($resource, $attribute) {
                $this->value = call_user_func($this->resolveCallback, $value, $resource, $attribute);
            });
        }
    }

    /**
     * Resolve the default value for an Action field.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return void
     */
    public function resolveForAction($request)
    {
        if (! is_null($this->value)) {
            return;
        }

        if ($this->defaultCallback instanceof Closure) {
            $this->defaultCallback = call_user_func($this->defaultCallback, $request);
        }

        $this->value = $this->defaultCallback;
    }

    /**
     * Resolve the given attribute from the given resource.
     *
     * @param  mixed  $resource
     * @param  string  $attribute
     * @return mixed
     */
    protected function resolveAttribute($resource, $attribute)
    {
        return Util::value(data_get($resource, str_replace('->', '.', $attribute)));
    }

    /**
     * Define the callback that should be used to display the field's value.
     *
     * @param  callable(mixed, mixed, string):mixed  $displayCallback
     * @return $this
     */
    public function displayUsing(callable $displayCallback)
    {
        $this->displayCallback = $displayCallback;

        return $this;
    }

    /**
     * Define the callback that should be used to resolve the field's value.
     *
     * @param  callable(mixed, mixed, ?string):mixed  $resolveCallback
     * @return $this
     */
    public function resolveUsing(callable $resolveCallback)
    {
        $this->resolveCallback = $resolveCallback;

        return $this;
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Illuminate\Database\Eloquent\Model|\Laravel\Nova\Support\Fluent  $model
     * @return mixed
     */
    public function fill(NovaRequest $request, $model)
    {
        return $this->fillInto($request, $model, $this->attribute);
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Illuminate\Database\Eloquent\Model|\Laravel\Nova\Support\Fluent  $model
     * @return mixed
     */
    public function fillForAction(NovaRequest $request, $model)
    {
        return $this->fill($request, $model);
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Illuminate\Database\Eloquent\Model|\Laravel\Nova\Support\Fluent  $model
     * @param  string  $attribute
     * @param  string|null  $requestAttribute
     * @return mixed
     */
    public function fillInto(NovaRequest $request, $model, $attribute, $requestAttribute = null)
    {
        return $this->fillAttribute($request, $requestAttribute ?? $this->attribute, $model, $attribute);
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  string  $requestAttribute
     * @param  \Illuminate\Database\Eloquent\Model|\Laravel\Nova\Support\Fluent  $model
     * @param  string  $attribute
     * @return mixed
     */
    protected function fillAttribute(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if (isset($this->fillCallback)) {
            return call_user_func($this->fillCallback, $request, $model, $attribute, $requestAttribute);
        }

        return $this->fillAttributeFromRequest($request, $requestAttribute, $model, $attribute);
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Illuminate\Database\Eloquent\Model|\Laravel\Nova\Support\Fluent  $model
     * @param  string  $requestAttribute
     * @param  object  $model
     * @param  string  $attribute
     * @return mixed
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            tap($request->input($requestAttribute), function ($value) use ($model, $attribute) {
                $value = $this->isValidNullValue($value) ? null : $value;

                $this->fillModelWithData($model, $value, $attribute);
            });
        }
    }

    /**
     * Fill the model's attribute with data.
     *
     * @param  \Illuminate\Database\Eloquent\Model|\Laravel\Nova\Support\Fluent  $model
     * @param  mixed  $value
     * @return void
     */
    public function fillModelWithData($model, $value, string $attribute)
    {
        $attributes = [Str::replace('.', '->', $attribute) => $value];

        $model->forceFill($attributes);
    }

    /**
     * Determine if the field supports null values.
     *
     * @return bool
     */
    protected function isNullable()
    {
        return $this->nullable;
    }

    /**
     * @return Field
     */
    public function compact(bool $compact = true)
    {
        $this->compact = $compact;

        return $this;
    }

    /**
     * Determine if the given value is considered a valid null value
     * if the field supports them.
     *
     * @deprecated Use "isValidNullValue"
     *
     * @param  mixed  $value
     * @return bool
     */
    protected function isNullValue($value)
    {
        return $this->isValidNullValue($value);
    }

    /**
     * Determine if the given value is considered a valid null value
     * if the field supports them.
     *
     * @param  mixed  $value
     * @return bool
     */
    public function isValidNullValue($value)
    {
        if (! $this->isNullable()) {
            return false;
        }

        return $this->valueIsConsideredNull($value);
    }

    /**
     * Determine if the given value is considered null.
     *
     * @param  mixed  $value
     * @return bool
     */
    protected function valueIsConsideredNull($value)
    {
        return is_callable($this->nullValues) ? ($this->nullValues)($value) : in_array(
            $value,
            (array) $this->nullValues
        );
    }

    /**
     * Specify a callback that should be used to hydrate the model attribute for the field.
     *
     * @param  callable(\Laravel\Nova\Http\Requests\NovaRequest, \Illuminate\Database\Eloquent\Model|\Laravel\Nova\Support\Fluent, string, string):mixed  $fillCallback
     * @return $this
     */
    public function fillUsing($fillCallback)
    {
        $this->fillCallback = $fillCallback;

        return $this;
    }

    /**
     * Specify that this field should be sortable.
     *
     * @param  bool  $value
     * @return $this
     */
    public function sortable($value = true)
    {
        if (! $this->computed()) {
            $this->sortable = $value;
        }

        return $this;
    }

    /**
     * Return the sortable uri key for the field.
     *
     * @return string
     */
    public function sortableUriKey()
    {
        return $this->attribute;
    }

    /**
     * Indicate that the field should be nullable.
     *
     * @param  bool  $nullable
     * @param  array<int, mixed>|\Closure  $values
     * @return $this
     */
    public function nullable($nullable = true, $values = null)
    {
        $this->nullable = $nullable;

        if ($values !== null) {
            $this->nullValues($values);
        }

        return $this;
    }

    /**
     * Specify nullable values.
     *
     * @param  array<int, mixed>|\Closure  $values
     * @return $this
     */
    public function nullValues($values)
    {
        $this->nullValues = $values;

        return $this;
    }

    /**
     * Determine if the field is computed.
     *
     * @return bool
     */
    public function computed()
    {
        return (is_callable($this->attribute) && ! is_string($this->attribute)) || $this->attribute == 'ComputedField';
    }

    /**
     * Get the component name for the field.
     *
     * @return string
     */
    public function component()
    {
        if (isset(static::$customComponents[get_class($this)])) {
            return static::$customComponents[get_class($this)];
        }

        return $this->component;
    }

    /**
     * Set the component that should be used by the field.
     *
     * @param  string  $component
     * @return void
     */
    public static function useComponent($component)
    {
        static::$customComponents[get_called_class()] = $component;
    }

    /**
     * Set the callback used to determine if the field is readonly.
     *
     * @param  (callable(\Laravel\Nova\Http\Requests\NovaRequest):(bool))|bool|null  $callback
     * @return $this
     */
    public function readonly($callback = true)
    {
        $this->readonlyCallback = $callback;

        return $this;
    }

    /**
     * Determine if the field is readonly.
     *
     * @return bool
     */
    public function isReadonly(NovaRequest $request)
    {
        return with($this->readonlyCallback, function ($callback) use ($request) {
            if ($callback === true || (is_callable($callback) && call_user_func($callback, $request))) {
                $this->setReadonlyAttribute();

                return true;
            }

            return false;
        });
    }

    /**
     * Set the field to a readonly field.
     *
     * @return $this
     */
    protected function setReadonlyAttribute()
    {
        $this->withMeta(['extraAttributes' => ['readonly' => true]]);

        return $this;
    }

    /**
     * Set the text alignment of the field.
     *
     * @param  string  $alignment
     * @return $this
     */
    public function textAlign($alignment)
    {
        $this->textAlign = $alignment;

        return $this;
    }

    /**
     * Set the callback used to determine if the field is required.
     *
     * @param  (callable(\Laravel\Nova\Http\Requests\NovaRequest):(bool))|bool|null  $callback
     * @return $this
     */
    public function required($callback = true)
    {
        $this->requiredCallback = $callback;

        return $this;
    }

    /**
     * Determine if the field is required.
     *
     * @return bool
     */
    public function isRequired(NovaRequest $request)
    {
        return with($this->requiredCallback, function ($callback) use ($request) {
            if ($callback === true || (is_callable($callback) && call_user_func($callback, $request))) {
                return true;
            }

            if (! empty($this->attribute) && is_null($callback)) {
                if ($request->isResourceIndexRequest() || $request->isActionRequest()) {
                    return in_array('required', $this->getCreationRules($request)[$this->attribute]);
                }

                if ($request->isCreateOrAttachRequest()) {
                    return in_array('required', $this->getCreationRules($request)[$this->attribute]);
                }

                if ($request->isUpdateOrUpdateAttachedRequest()) {
                    return in_array('required', $this->getUpdateRules($request)[$this->attribute]);
                }
            }

            return false;
        });
    }

    /**
     * Set the width for the help text tooltip.
     *
     * @param  string  $helpWidth
     * @return $this
     *
     * @throws \Exception
     */
    public function helpWidth($helpWidth)
    {
        throw NovaException::helperNotSupported(__METHOD__, __CLASS__);
    }

    /**
     * Return the width of the help text tooltip.
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getHelpWidth()
    {
        throw NovaException::helperNotSupported(__METHOD__, __CLASS__);
    }

    /**
     * Set the callback to be used for determining the field's default value.
     *
     * @param  (\Closure(\Laravel\Nova\Http\Requests\NovaRequest):(mixed))|mixed  $callback
     * @return $this
     */
    public function default($callback)
    {
        $this->defaultCallback = $callback;

        return $this;
    }

    /**
     * Resolve the default value for the field.
     *
     * @return mixed
     */
    public function resolveDefaultValue(NovaRequest $request)
    {
        if ($this->requestShouldResolveDefaultValue($request)) {
            return $this->resolveDefaultCallback($request);
        }
    }

    /**
     * Resolve the default callback for the field.
     *
     * @return mixed
     */
    public function resolveDefaultCallback(NovaRequest $request)
    {
        if (is_null($this->value) && $this->defaultCallback instanceof Closure) {
            return call_user_func($this->defaultCallback, $request);
        }

        return $this->defaultCallback;
    }

    /**
     * Determine if request should resolve default value.
     *
     * @return bool
     */
    public function requestShouldResolveDefaultValue(NovaRequest $request)
    {
        return $request->isCreateOrAttachRequest() || $request->isActionRequest();
    }

    /**
     * Set the placeholder text for the field if supported.
     *
     * @param  string|null  $text
     * @return $this
     */
    public function placeholder($text)
    {
        $this->placeholder = $text;

        $this->withMeta(['extraAttributes' => ['placeholder' => $text]]);

        return $this;
    }

    /**
     * Set the field to be visible on the form.
     *
     * @return $this
     */
    public function show()
    {
        $this->visible = true;

        return $this;
    }

    /**
     * Set the field to be hidden on the form.
     *
     * @return $this
     */
    public function hide()
    {
        $this->visible = false;

        return $this;
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        /** @phpstan-ignore-next-line */
        return with(app(NovaRequest::class), function ($request) {
            return array_merge([
                'attribute' => $this->attribute,
                'component' => $this->component(),
                'compact' => $this->compact,
                'displayedAs' => $this->displayedAs,
                'fullWidth' => $this->fullWidth,
                'helpText' => $this->getHelpText(),
                'indexName' => $this->name,
                'inline' => $this->inline,
                'name' => $this->name,
                'nullable' => $this->nullable,
                'panel' => $this->panel,
                'placeholder' => $this->placeholder,
                'prefixComponent' => true,
                'readonly' => $this->isReadonly($request),
                'required' => $this->isRequired($request),
                'sortable' => $this->sortable,
                'sortableUriKey' => $this->sortableUriKey(),
                'stacked' => $this->stacked,
                'textAlign' => $this->textAlign,
                'uniqueKey' => sprintf(
                    '%s-%s-%s',
                    $this->attribute,
                    Str::slug($this->panel ?? 'default'),
                    $this->component()
                ),
                'usesCustomizedDisplay' => $this->usesCustomizedDisplay,
                'validationKey' => $this->validationKey(),
                'value' => $this->value ?? $this->resolveDefaultValue($request),
                'visible' => $this->visible,
                'withLabel' => $this->withLabel,
                'wrapping' => $this->wrapping,
            ], $this->serializeDependentField($request), $this->meta());
        });
    }
}
