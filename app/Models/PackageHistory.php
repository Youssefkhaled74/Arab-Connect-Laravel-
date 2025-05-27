<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'package_id',
        'branch_id',
        'price',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
