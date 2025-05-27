<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BranchPayment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'branch_payments';
}
