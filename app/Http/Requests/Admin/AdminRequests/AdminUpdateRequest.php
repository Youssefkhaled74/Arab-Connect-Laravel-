<?php

namespace App\Http\Requests\Admin\AdminRequests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateRequest extends FormRequest
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
        $user = auth()->guard('admin')->user();
        return [
            'name' => 'nullable|string|max:60',
            'password' => 'nullable|confirmed|max:30',
            'photo' => 'nullable|mimes:jpeg,png,jpg,webp,webm|max:2048',
            'email' => 'nullable|string|max:60|unique:admins,email,' . $user->id,
            'phone' => 'nullable|max:60|unique:admins,phone,' . $user->id,
        ];
    }
}
