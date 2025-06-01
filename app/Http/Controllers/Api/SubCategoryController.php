<?php

namespace App\Http\Controllers\Api;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;

class SubCategoryController extends Controller
{
    public function getByCategory(Request $request, $category_id)
    {
        //check if the category active 
        $category = Category::get()->where('id', $category_id)->first();
        if($category->is_activate == 0 ){
            return response()->json([
                'status' =>200,
                'msg' => 'The category not Active'
            ]);
        }
        $perPage = $request->get('per_page', 10);

        $subCategories = SubCategory::where('category_id', $category_id)
            ->paginate($perPage);

        return response()->json([
            'status' => 200,
            'msg' => 'success',
            'data' => $subCategories
        ], 200);
    }
}
