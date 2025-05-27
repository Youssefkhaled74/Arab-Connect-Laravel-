<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayChange extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function branchChange()
    {
        return $this->belongsTo(BranchChange::class, 'branch_id');
    }
}
