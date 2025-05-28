<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'flag',
        'mobile_code',
        'code',
    ];
    public $timestamps = false;

    protected $table = 'countries';

    /**
     * Get the users associated with the country.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'country_code', 'code');
    }

    /**
     * Get the branches associated with the country.
     */
    public function branches()
    {
        return $this->hasMany(Branch::class, 'country_code', 'code');
    }
}
