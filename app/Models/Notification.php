<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'branch_id',
        'title',
        'sent_at',
        'message',
    ];

    // Relationship with the User model
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Relationship with the Branch model
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
