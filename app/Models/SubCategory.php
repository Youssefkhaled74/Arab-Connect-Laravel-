<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'name',
        'img',
    ];



    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    public function branches()
    {
        return $this->hasMany(\App\Models\Branch::class, 'sub_category_id');
    }
}
