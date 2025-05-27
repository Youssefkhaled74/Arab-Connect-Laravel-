<?php

namespace App\Http\Requests\Admin\AdminRequests;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
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
            
            'email' => 'required|exists:admins,email|max:60',
            'password' => 'required|max:30'
        ];
    }
}
