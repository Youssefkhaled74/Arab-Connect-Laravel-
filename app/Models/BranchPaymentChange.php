<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BranchPaymentChange extends Model
{
    use HasFactory;

    protected $table = 'branch_payment_changes';
    protected $guarded = [];

    public function branchChange()
    {
        return $this->belongsTo(BranchChange::class, 'branch_id');
    }


    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }


}
