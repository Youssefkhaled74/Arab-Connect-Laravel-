<?php

namespace App\Http\ServicesLayer\Api\BranchServices;

use App\Models\Day;
use App\Models\Branch;
use App\Models\Favorite;
use App\Models\DayChange;
use Illuminate\Support\Str;
use App\Models\BranchChange;
use App\Models\BranchPaymentChange;

class BranchService
{
    public $branch;
    public $day;
    public $favorite;

    public function __construct(Branch $branch, Day $day, Favorite $favorite)
    {
        $this->branch = $branch;
        $this->day = $day;
        $this->favorite = $favorite;
    }

    public function userBranches($perPage = 10, $page = 1)
    {
        $perPage = request()->get('per_page', $perPage);
        $page = request()->get('page', $page);

        $branches = auth()->user()->branches()
            ->with('payments', 'subCategory')
            ->where('expire_at', '>=', now())
            ->paginate($perPage, ['*'], 'page', $page);

        $branches->getCollection()->transform(function ($branch) {
            $branch->sub_category_name = $branch->subCategory ? $branch->subCategory->name : null;
            $branch->country_id = $branch->country_id ? (int) $branch->country_id : null;
            $branch->img = $branch->img
                ? env('APP_URL') . '/public/' . $branch->img
                : null;
            return $branch;
        });

        return [
            'branches' => $branches->items(),
            'page' => $branches->currentPage(),
            'per_page' => $branches->perPage(),
            'last_page' => $branches->lastPage(),
            'total' => $branches->total(),
        ];
    }
    public function details($id)
    {
        $branch = $this->branch->where('id', $id)->with([
            'payments',
            'days',
            'reviews.user', // eager load user for each review
            'related_branches' => function ($query) use ($id) {
                $query->whereNotIn('id', [$id]);
            }
        ])->first();

        $hasReview = false;
        if ($branch && auth()->check()) {
            $hasReview = $branch->reviews()->where('user_id', auth()->id())->exists();
        }

        $averageStars = 0;
        $reviews = [];
        if ($branch) {
            $branch->country_id = $branch->country_id ? (int) $branch->country_id : null;
            $branch->img = $branch->img ? env('APP_URL') . '/public/' . $branch->img : null;

            // Cast country_id and img for each related branch
            if ($branch->related_branches) {
                $branch->related_branches->transform(function ($related) {
                    $related->country_id = $related->country_id ? (int) $related->country_id : null;
                    $related->img = $related->img ? env('APP_URL') . '/public/' . $related->img : null;
                    return $related;
                });
            }

            // Get all reviews with user info
            $reviews = $branch->reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'user' => [
                        'id' => $review->user->id ?? null,
                        'name' => $review->user->name ?? null,
                    ],
                    'stars' => $review->stars,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at,
                ];
            });

            // Calculate average stars
            $averageStars = $branch->reviews->avg('stars') ?? 0;
        }

        // Add review key, reviews, and average_stars to the response
        $branchArray = $branch ? $branch->toArray() : [];
        $branchArray['review'] = $hasReview;
        $branchArray['reviews'] = $reviews;
        $branchArray['average_stars'] = round($averageStars, 2);

        return $branchArray;
    }

    public function store($request)
    {
        if ($request->hasFile('tax_card')) {
            $tax_card = uploadIamge($request->file('tax_card'), 'branches');
        }
        if ($request->hasFile('commercial_register')) {
            $commercial_register = uploadIamge($request->file('commercial_register'), 'branches');
        }

        // Handle single image upload with field name 'img'
        $img = null;
        if ($request->hasFile('img')) {
            $img = uploadIamge($request->file('img'), 'branches');
        }

        $map_location = generateGoogleMapsLink($request->lat, $request->lon);

        $branch = $this->branch->create([
            'name' => $request->name ?? null,
            'mobile' => $request->mobile ?? null,
            'location' => $request->location ?? null,
            'map_location' => $map_location ?? null,
            'category_id' => $request->category_id ?? null,
            'country_id' => $request->country_id ?? null,
            'email' => $request->email ?? null,
            'face' => $request->face ?? null,
            'insta' => $request->insta ?? null,
            'tiktok' => $request->tiktok ?? null,
            'website' => $request->website ?? null,
            'lat' => $request->lat ?? null,
            'lon' => $request->lon ?? null,
            'img' => $img,
            'tax_card' => $tax_card ?? null,
            'commercial_register' => $commercial_register ?? null,
            'uuid' => generateCustomUUID(),
            'owner_id' => auth()->guard('api')->user()->id,
            'expire_at' => now()->addMonths(6),
            'all_days' => $request->all_days ?? 0,
        ]);

        if (isset($request->payments) && count($request->payments) > 0) {
            $branch->payments()->sync(array_values($request->payments));
        }
        if (isset($request->days) && count($request->days) > 0) {
            $days = [];
            foreach ($request->days as $day) {
                if ((int)$day['day'] > 0 && 7 >= (int)$day['day']) {
                    $days[] = [
                        'from' => (int)$day['off'] == 0 ? $day['from'] ?? '08:00' : '',
                        'to' => (int)$day['off'] == 0 ? $day['to'] ?? '22:00' : '',
                        'off' => $day['off'],
                        'day' => $day['day'],
                        'branch_id' => $branch->id
                    ];
                }
            }
            $this->day->insert($days);
        }
        return $branch;
    }

    // public function update($request, $id)
    // {
    //     // $branchimgs = explode(',', $branch->imgs);
    //     // $array2 = ['admin/assets/images/branches/173036543252846052.jpg'];
    //     // $imgs = array_intersect($branchimgs, $array2);

    //     $branch = $this->branch->where('id', $id)->where('owner_id', auth()->guard('api')->user()->id)->first();
    //     if (is_null($branch)) {
    //         return 0;
    //     }
    //     if (!$request->hasFile('tax_card') == null) {
    //         $tax_card = uploadIamge($request->file('tax_card'), 'branches'); // function on helper file to upload file
    //     }
    //     if (!$request->hasFile('commercial_register') == null) {
    //         $commercial_register = uploadIamge($request->file('commercial_register'), 'branches'); // function on helper file to upload file
    //     }
    //     $imgs = '';
    //     $branch_imgs_arr = explode(',', $branch->imgs);
    //     $request_imgs_arr = explode(',', $request->imgs);
    //     foreach ($request_imgs_arr as $index => $img) {
    //         if (str()->contains($img, "https://egypin.clincher.evyx.one/public/")) {
    //             $imgs .= str_replace("https://egypin.clincher.evyx.one/public/", '', $img);
    //         } else {
    //             $imgs .= $img;
    //         }
    //         if ($index !== array_key_last($request_imgs_arr)) {
    //             $imgs .= ',';
    //         }
    //     }
    //     if (!$request->hasFile('new_imgs') == null) {
    //         $imgs .= ',' . uploadIamges($request->file('new_imgs'), 'branches'); // function on helper file to upload file
    //     }
    // 	if (strpos($imgs, ',') === 0) {
    // 		$imgs = ltrim($imgs, ',');
    // 	}

    //     if($request->name) $branch->name = $request->name;
    //     if($request->mobile) $branch->mobile = $request->mobile;
    //     if($request->location) $branch->location = $request->location;
    //     if($request->map_location) $branch->map_location = $request->map_location;
    //     $branch->category_id = $request->category_id ?? null;
    //     $branch->email = $request->email ?? null;
    //     $branch->face = $request->face ?? null;
    //     $branch->insta = $request->insta ?? null;
    //     $branch->tiktok = $request->tiktok ?? null;
    //     $branch->website = $request->website ?? null;
    //     $branch->lon = $request->lon ?? null;
    //     $branch->lat = $request->lat ?? null;
    //     if(!is_null($imgs)) $branch->imgs = $imgs;
    //     if(isset($tax_card) && !is_null($tax_card)) $branch->tax_card = $tax_card;
    //     if(isset($commercial_register) && !is_null($commercial_register)) $branch->commercial_register = $commercial_register;
    //     $branch->is_activate = 0;
    //     $branch->save();
    //     if (isset($request->payments) && count($request->payments) > 0) {
    //         $branch->payments()->sync(array_values($request->payments));
    //     }
    //     if (isset($request->days) && count($request->days) > 0) {
    //         $days = [];
    //         foreach ($request->days as $day) {
    //             if ((int)$day['day'] > 0 && 7 >= (int)$day['day']) {
    //                 $days [] = [
    //                     'from' => (int)$day['off'] == 0 ? $day['from'] ?? '08:00' : '',
    //                     'to' => (int)$day['off'] == 0 ? $day['to'] ?? '22:00' : '',
    //                     'off' => $day['off'],
    //                     'day' => $day['day'],
    //                     'branch_id' => $branch->id
    //                 ];
    //             }
    //         }
    //         if (count($days)) $branch->days()->delete();
    //         $this->day->insert($days);
    //     }
    //     return $branch;
    // }



    public function update($request, $id)
    {
        $branch = $this->branch->where('id', $id)->where('owner_id', auth()->guard('api')->user()->id)->first();
        if (is_null($branch)) {
            return 0;
        }

        // Handle single image upload
        $img = $request->hasFile('img') ? uploadIamge($request->file('img'), 'branches') : $branch->img;
        $tax_card = $request->hasFile('tax_card') ? uploadIamge($request->file('tax_card'), 'branches') : $branch->tax_card;
        $commercial_register = $request->hasFile('commercial_register') ? uploadIamge($request->file('commercial_register'), 'branches') : $branch->commercial_register;

        if ($request->has('lat') && $request->has('lon')) {
            $map_location = generateGoogleMapsLink($request->lat, $request->lon);
        } else {
            $map_location = $branch->map_location;
        }

        $branchChange = BranchChange::create([
            'branch_id' => $branch->id,
            'name' => $request->name ?? $branch->name,
            'mobile' => $request->mobile ?? $branch->mobile,
            'location' => $request->location ?? $branch->location,
            'map_location' => $map_location,
            'email' => $request->email ?? $branch->email,
            'face' => $request->face ?? $branch->face,
            'insta' => $request->insta ?? $branch->insta,
            'tiktok' => $request->tiktok ?? $branch->tiktok,
            'website' => $request->website ?? $branch->website,
            'lon' => $request->lon ?? $branch->lon,
            'lat' => $request->lat ?? $branch->lat,
            'img' => $img,
            'tax_card' => $tax_card,
            'commercial_register' => $commercial_register,
            'country_id' => $request->country_id ?? $branch->country_id,
            'is_activate' => 0,
            'all_days' => $request->all_days ?? $branch->all_days,
        ]);

        if (isset($request->days) && count($request->days) > 0) {
            foreach ($request->days as $day) {
                DayChange::create([
                    'branch_id' => $branchChange->id,
                    'day' => $day['day'],
                    'from' => $day['off'] == 0 ? $day['from'] : null,
                    'to' => $day['off'] == 0 ? $day['to'] : null,
                    'off' => $day['off']
                ]);
            }
        }
        if (isset($request->payments) && count($request->payments) > 0) {
            foreach ($request->payments as $payment) {
                BranchPaymentChange::create([
                    'branch_id' => $branchChange->id,
                    'payment_method_id' => $payment
                ]);
            }
        }
        return $branchChange;
    }

    private function processImages($request, $branch)
    {
        $imgs = '';
        if ($request->hasFile('new_imgs')) {
            $temporary_imgs = uploadIamges($request->file('new_imgs'), 'branches');
            $imgs .= ',' . $temporary_imgs;
        } else {
            $imgs = $request->imgs;
        }
        return ltrim($imgs, ',');
    }

    public function getFavorites($perPage = 10, $page = 1)
    {
        return auth()->guard('api')->user()->favorites()
            ->where('branches.is_published', 1)
            ->where('branches.expire_at', '>=', now())
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function favorites($id)
    {

        if (auth()->guard('api')->user()->favorites()->find($id)) {
            $this->removeFavorites($id);
            return responseJson(200, "success removed");
        } else {
            $this->addFavorites($id);
            return responseJson(200, "success added");
        }
    }

    public function addFavorites($id)
    {
        return auth()->user()->favorites()->attach($id);
    }

    public function removeFavorites($id)
    {
        return auth()->user()->favorites()->detach($id);
    }
}
