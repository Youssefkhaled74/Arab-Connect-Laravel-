<?php

namespace App\Http\Requests\Admin\UserRequests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'name' => 'required|string|max:254',
            'email' => 'required|string|max:254|unique:users,email',
            'mobile' => 'required|string|max:254|unique:users,mobile',
            'password' => 'required|confirmed|max:30',
            'user_type' => 'required|in:1,2',
        ];
    }
}
