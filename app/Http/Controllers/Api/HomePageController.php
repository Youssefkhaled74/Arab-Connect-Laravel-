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

        $query = Branch::whereNotNull('lat')
            ->whereNotNull('lon')
            ->where('lat', '!=', '')
            ->where('lon', '!=', '')
            ->whereRaw('lat REGEXP "^-?[0-9]+(\.[0-9]+)?$"')
            ->whereRaw('lon REGEXP "^-?[0-9]+(\.[0-9]+)?$"');

        if ($lat !== null && $lon !== null) {
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
        }

        $branches = $query->paginate($perPage, ['*'], 'page', $page);

        // Add 'how_far' key if distance exists, and format img as full URL
        $branchesData = collect($branches->items())->map(function ($branch) {
            if (isset($branch->distance)) {
                $branch->how_far = round($branch->distance, 2);
                unset($branch->distance);
            }
            $branch->img = $branch->img
                ? env('APP_URL') . '/public/' . $branch->img
                : null;
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
