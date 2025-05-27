<?php

namespace App\Http\Requests\Admin\BlogRequests;

use Illuminate\Foundation\Http\FormRequest;

class BlogStoreRequest extends FormRequest
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
            'title' => 'required|string|max:1255|unique:blogs,title',
            'slug' => 'required|string|max:1255|unique:blogs,slug',
            'description' => 'required|string|max:12550',
            'category_id' => 'required|exists:categories,id',
            
            'imgs' => 'nullable|array',
            'imgs.*' => 'image|max:2048',

            'meta_title' => 'nullable|string|max:1255',
            'meta_description' => 'nullable|string|max:1255',
            'meta_tags' => 'nullable|string|max:1255',
            'meta_keywords' => 'nullable|string|max:1255',
        ];
    }
}
