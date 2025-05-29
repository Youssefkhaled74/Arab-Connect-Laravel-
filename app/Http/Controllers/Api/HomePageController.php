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
        $validator = Validator::make($request->all(), [
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return responseJson(400, "Bad Request", $validator->errors()->first());
        }

        $lat = $request->lat;
        $lon = $request->lon;

        // Haversine formula for distance in km
        $branches = Branch::select('*', DB::raw(
            "(6371 * acos(cos(radians($lat)) * cos(radians(lat)) * cos(radians(lon) - radians($lon)) + sin(radians($lat)) * sin(radians(lat)))) AS distance"
        ))
            ->orderBy('distance')
            ->limit(20)
            ->get();

        // Add 'how_far' key to each branch
        $branches->transform(function ($branch) {
            $branch->how_far = round($branch->distance, 2); // in km
            unset($branch->distance);
            return $branch;
        });

        return responseJson(200, "success", $branches);
    }
}
