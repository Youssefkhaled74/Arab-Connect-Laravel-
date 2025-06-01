<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Blog;
use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public $user;
    public $category;
    public $blog;
    public $about;
    public $paymentMethod;

    public function __construct(User $user, Category $category, Blog $blog, PaymentMethod $paymentMethod, About $about)
    {
        $this->user = $user;
        $this->category = $category;
        $this->blog = $blog;
        $this->about = $about;
        $this->paymentMethod = $paymentMethod;
        $this->middleware('auth:api', ['except' => ['test', 'category', 'categories', 'payments', 'blogs', 'blog', 'abouts', 'settings']]);
    }

    public function subCategoryBranches(Request $request, $subCategoryId)
    {
        $perPage = $request->get('per_page', 10); // Default to 10

        $subCategory = \App\Models\SubCategory::find($subCategoryId);

        if (!$subCategory) {
            return responseJson(404, "SubCategory not found");
        }

        $branches = $subCategory->branches()->paginate($perPage);
        
        $branchesData = collect($branches->items())->map(function ($branch) use ($subCategory) {
            $branch->sub_category_name = $subCategory->name;
            $branch->img = $branch->img
                ? env('APP_URL') . '/public/' . $branch->img
                : null;
            return $branch;
        });

        return responseJson(200, "success", [
            'sub_category' => $subCategory,
            'branches' => $branchesData,
            'current_page' => $branches->currentPage(),
            'last_page' => $branches->lastPage(),
            'per_page' => $branches->perPage(),
            'total' => $branches->total(),
        ]);
    }

    public function categoriesold(Request $request, $page = 0)
    {
        $query = $request->get('search');

        if (!is_null($query)) {
            $categories = $this->category->active()
                ->orderBy('id', 'ASC')
                ->modelSearch($query)
                ->paginate(5);
        } else {
            $categories = $this->category->active()
                ->unArchive()
                ->orderBy('id', 'ASC')
                ->offset(5 * $page)
                ->limit(5)
                ->get();
        }

        return responseJson(200, "success", $categories);
    }

    public function categories(Request $request)
    {
        $perPage = $request->get('per_page', 10); // Default to 10 if not provided

        $categories = $this->category->active()
            ->unArchive()
            ->orderBy('id', 'ASC')
            ->paginate($perPage);

        // Map categories to add full image URL
        $categoriesData = collect($categories->items())->map(function ($category) {
            $category->img = $category->img
                ? env('APP_URL') . '/public/' . $category->img
                : null;
            return $category;
        });

        $count = $this->category->active()->unArchive()->count();
        $description = DB::table('nova_settings')->where('key', 'description_category')->select('value')->first();

        return responseJson(200, "success", [
            'data' => $categoriesData,
            'count' => $count,
            'page' => $categories->currentPage(),
            'current_page' => $categories->currentPage(),
            'last_page' => $categories->lastPage(),
            'per_page' => $categories->perPage(),
            'total' => $categories->total(),
        ]);
    }
    public function payments($page = 0)
    {
        $payments = $this->paymentMethod->active()->unArchive()->orderBy('id', 'DESC')->offset(PAGINATION_COUNT_FRONT * $page)->limit(PAGINATION_COUNT_FRONT)->get();
        return responseJson(200, "success", $payments);
    }

    public function blogs($page = 0)
    {
        $blogs = $this->blog->active()->unArchive()->orderBy('id', 'DESC')->offset(PAGINATION_COUNT_FRONT * $page)->limit(PAGINATION_COUNT_FRONT)->get();
        return responseJson(200, "success", $blogs);
    }

    public function blog($id = 0)
    {
        $blog = $this->blog->active()->unArchive()->where('id', $id)->get();
        return responseJson(200, "success", $blog);
    }

    public function abouts($type = 1)
    {
        $limit = $type == 2 ? PAGINATION_COUNT_FRONT : 1;
        $abouts = $this->about->active()->unArchive()->orderBy('id', 'DESC')->where('type', $type)->limit($limit)->get();
        return responseJson(200, "success", $abouts);
    }

    public function test(Request $request)
    {
        return $request->all();
    }

    public function settings()
    {
        $settings = DB::table('nova_settings')->where('key', '!=', 'description_category')->get();
        return responseJson(200, "success", $settings);
    }
}
