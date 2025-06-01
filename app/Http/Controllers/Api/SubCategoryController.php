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
        // Check if the category is active
        $category = Category::where('id', $category_id)->first();
        if (!$category || $category->is_activate == 0) {
            return response()->json([
                'status' => 200,
                'msg' => 'The category not Active'
            ]);
        }

        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);

        $subCategories = SubCategory::where('category_id', $category_id)
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'status' => 200,
            'msg' => 'success',
            'data' => $subCategories->items(),
            'page' => $subCategories->currentPage(),
            'last_page' => $subCategories->lastPage(),
            'per_page' => $subCategories->perPage(),
            'total' => $subCategories->total(),
        ], 200);
    }
}
