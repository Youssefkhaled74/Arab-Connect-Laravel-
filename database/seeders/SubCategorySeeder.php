<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubCategory;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 5) as $categoryId) {
            foreach (range(1, 5) as $i) {
                SubCategory::create([
                    'category_id' => $categoryId,
                    'name' => "Sub Category {$i} for Category {$categoryId}",
                    'img' => null, // or set a default image path if needed
                ]);
            }
        }
    }

}
