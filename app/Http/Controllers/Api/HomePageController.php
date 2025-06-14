<?php

namespace App\Http\Controllers\Api;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class HomePageController extends Controller
{
    public function nearestBranches(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        $lat = $request->get('lat');
        $lon = $request->get('lon');

        $query = Branch::where('is_published', 1)
            ->whereNotNull('lat')
            ->whereNotNull('lon')
            ->where('lat', '!=', '')
            ->where('lon', '!=', '')
            ->whereRaw('lat REGEXP "^-?[0-9]+(\.[0-9]+)?$"')
            ->whereRaw('lon REGEXP "^-?[0-9]+(\.[0-9]+)?$"');

        if ($lat !== null && $lat !== '' && $lon !== null && $lon !== '') {
            // Validate coordinates
            $validator = Validator::make($request->all(), [
                'lat' => 'required|numeric',
                'lon' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return responseJson(400, "Bad Request", $validator->errors()->first());
            }

            $query = $query->select('*', DB::raw(
                "(6371 * acos(
                cos(radians($lat)) * cos(radians(lat)) * cos(radians(lon) - radians($lon)) +
                sin(radians($lat)) * sin(radians(lat))
            )) AS distance"
            ))->orderBy('distance');
        } else {
            $query = $query->inRandomOrder();
        }

        $branches = $query->paginate($perPage, ['*'], 'page', $page);

        $branchesData = collect($branches->items())->map(function ($branch) {
            $branch->how_far = isset($branch->distance) ? round($branch->distance, 2) : null;
            unset($branch->distance);

            $branch->img = $branch->img
                ? env('APP_URL') . '/public/' . $branch->img
                : null;
            $branch->sub_category_name = $branch->subCategory ? $branch->subCategory->name : null;
            $branch->country_id = $branch->country_id ? (int) $branch->country_id : null;
            $branch->country_code = $branch->country_code ?? null;
            return $branch;
        });

        return responseJson(200, "success", [
            'branches' => $branchesData,
            'page' => $branches->currentPage(),
            'per_page' => $branches->perPage(),
            'last_page' => $branches->lastPage(),
            'total' => $branches->total(),
        ]);
    }

    public function searchBranches(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'country_id' => 'nullable|integer|exists:countries,id',
            'per_page' => 'nullable|integer|min:1',
            'page' => 'nullable|integer|min:1',
        ]);
        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }

        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        $name = $request->get('name');
        $countryId = $request->get('country_id');

        $query = Branch::where('is_published', 1)
            ->where('name', 'like', '%' . $name . '%');

        if ($countryId) {
            $query->where('country_id', $countryId);
        }

        $branches = $query->paginate($perPage, ['*'], 'page', $page);

        $branchesData = collect($branches->items())->map(function ($branch) {
            $branch->img = $branch->img
                ? env('APP_URL') . '/public/' . $branch->img
                : null;
            $branch->sub_category_name = $branch->subCategory ? $branch->subCategory->name : null;
            $branch->country_id = $branch->country_id ? (int) $branch->country_id : null;
            $branch->country_code = $branch->country_code ?? null;
            return $branch;
        });

        return responseJson(200, "success", [
            'branches' => $branchesData,
            'page' => $branches->currentPage(),
            'per_page' => $branches->perPage(),
            'last_page' => $branches->lastPage(),
            'total' => $branches->total(),
        ]);
    }


    //checkToken
    public function checkToken(Request $request)
    {
        if (auth('api')->check()) {
            return responseJson(200, 'Token valid', true);
        } else {
            return responseJson(404, 'Token invalid', false);
        }
    }
}
