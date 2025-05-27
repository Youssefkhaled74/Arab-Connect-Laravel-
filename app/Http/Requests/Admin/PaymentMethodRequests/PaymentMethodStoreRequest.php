<?php

namespace App\Http\Requests\Admin\PaymentMethodRequests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentMethodStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public static function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public static function rules()
    {
        return [
            'name' => 'required|string|max:254|unique:payment_methods,name'
        ];
    }
}
