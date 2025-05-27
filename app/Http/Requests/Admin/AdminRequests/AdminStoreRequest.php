<?php

namespace App\Http\Requests\Admin\AdminRequests;

use Illuminate\Foundation\Http\FormRequest;

class AdminStoreRequest extends FormRequest
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
            'name' => 'required|string|max:60',
            'password' => 'required|confirmed|max:30',
            'photo' => 'nullable|mimes:jpeg,png,jpg,webp,webm|max:2048',
            'email' => 'required|string|unique:admins,email|max:60',
            'phone' => 'nullable|unique:admins,phone|max:60',
        ];
    }
}
