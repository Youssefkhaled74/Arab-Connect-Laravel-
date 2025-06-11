<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;	

class BranchChange extends Model
{
    use HasFactory,HasRoles,LogsActivity;

    protected $fillable = [
        'id',
        'branch_id',
        'name',
        'mobile',
        'location',
        'map_location',
        'email',
        'face',
        'insta',
        'tiktok',
        'website',
        'lon',
        'lat',
        'img',
        'tax_card',
        'commercial_register',
        'is_activate',
        'old_imgs',
        'all_days',
    ];

    protected static $logAttributes = ['*']; 
    protected static $logName = 'Branch Changes';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() 
            ->logOnlyDirty() 
            ->useLogName('Branch Changes')
			->setDescriptionForEvent(fn(string $eventName) => match ($eventName) {
				'created' => 'تم إنشاء هذا النموذج بنجاح',
				'updated' => 'تم تحديث هذا النموذج بنجاح',
				'deleted' => 'تم حذف هذا النموذج بنجاح',
				default => "تم تنفيذ الحدث: {$eventName}",
			})            
			->dontSubmitEmptyLogs();
    }

    public function dayChanges()
    {
        return $this->hasMany(DayChange::class, 'branch_id');
    }
    public function payment_methods()
    {
        return $this->hasMany(BranchPaymentChange::class ,'branch_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($branch) {
            if ($branch->imgs) {
                $images = explode(',', $branch->imgs);
                foreach ($images as $image) {
                    $imagePath = public_path($image);
                    if (File::exists($imagePath)) {
                        File::delete($imagePath);
                    }
                }
            }
            if ($branch->tax_card) {
                    $imagePath = public_path($branch->tax_card);
                    if (File::exists($imagePath)) {
                        File::delete($imagePath);
                    }
            }
            if ($branch->commercial_register) {
                    $imagePath = public_path($branch->commercial_register);
                    if (File::exists($imagePath)) {
                        File::delete($imagePath);
                    }
            }
        });
    }
}
