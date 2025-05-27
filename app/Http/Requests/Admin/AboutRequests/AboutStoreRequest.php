<?php

namespace App\Http\Requests\Admin\AboutRequests;

use Illuminate\Foundation\Http\FormRequest;

class AboutStoreRequest extends FormRequest
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
            'content' => 'required|string|max:12550',
            'type' => 'required|in:1,2,3',
            'photo' => 'required|image|max:2048',
        ];
    }
}
