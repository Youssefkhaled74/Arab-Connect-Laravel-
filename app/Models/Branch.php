<?php

namespace App\Models;

use Carbon\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Branch extends Model implements HasMedia
{
    use HasRoles, LogsActivity;
    use InteractsWithMedia, Notifiable;
    protected $table = 'branches';
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'country_code',
        'location',
        'map_location',
        'img',
        'tax_card',
        'commercial_register',
        'face',
        'insta',
        'tiktok',
        'website',
        'category_id',
        'uuid',
        'owner_id',
        'lon',
        'lat',
        'is_verified',
        'deleted_at',
        'expire_at',
        'all_days',
        'three_month_email_sent_at',
        'one_month_email_sent_at'
    ];

    protected static $logAttributes = ['*'];
    protected static $logName = 'Branches';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('Branches')
            ->setDescriptionForEvent(fn(string $eventName) => match ($eventName) {
                'created' => 'تم إنشاء هذا النموذج بنجاح',
                'updated' => 'تم تحديث هذا النموذج بنجاح',
                'deleted' => 'تم حذف هذا النموذج بنجاح',
                default => "تم تنفيذ الحدث: {$eventName}",
            })
            ->dontSubmitEmptyLogs();
    }

    public $timestamps = true;
    public $appends = ['is_favorate'];

    public function getIsFavorateAttribute()
    {
        if (auth()->guard('api')->check()) {
            return $this->is_favorate()->exists();
        }
        return false;
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', 1);
    }

    public function scopeUnPublished($query)
    {
        return $query->where('is_published', 0);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', 1);
    }

    public function scopeUnVerified($query)
    {
        return $query->where('is_verified', 0);
    }

    public function scopeActive($query)
    {
        return $query->where('is_activate', 1);
    }

    public function scopeUnActive($query)
    {
        return $query->where('is_activate', 0);
    }

    public function scopeArchive($query)
    {
        return $query->whereNotNull('deleted_at');
    }

    public function scopeUnArchive($query)
    {
        return $query->whereNull('deleted_at');
    }


    public function fildes()
    {
        return [
            'name' => '',
            'email' => '',
            'mobile' => '',
            'location' => '',
            'map_location' => '',
            'imgs' => '',
            'tax_card' => '',
            'commercial_register' => '',
            'face' => '',
            'insta' => '',
            'tiktok' => '',
            'website' => '',
            'category_id' => '',
            'lon' => '',
            'lat' => '',
            'deleted_at' => '',
            'expire_at' => '',
            'all_days' => ''
        ];
    }

    public function scopeModelSearch($model, $query)
    {
        return $model->latest()->where('id', 'LIKE', '%' . $query . '%')
            ->orWhere('name', 'LIKE', '%' . $query . '%')
            ->orWhere('email', 'LIKE', '%' . $query . '%')
            ->orWhere('mobile', 'LIKE', '%' . $query . '%')
            ->orWhere('location', 'LIKE', '%' . $query . '%')
            ->orWhere('uuid', 'LIKE', '%' . $query . '%')
            ->unArchive()->limit(PAGINATION_COUNT_FRONT)->get();
    }

    public function scopeModelSearchInArchives($model, $query)
    {
        return $model->latest()->where('id', 'LIKE', '%' . $query . '%')
            ->orWhere('name', 'LIKE', '%' . $query . '%')
            ->orWhere('email', 'LIKE', '%' . $query . '%')
            ->orWhere('mobile', 'LIKE', '%' . $query . '%')
            ->orWhere('location', 'LIKE', '%' . $query . '%')
            ->orWhere('uuid', 'LIKE', '%' . $query . '%')
            ->archive()->limit(PAGINATION_COUNT_FRONT)->get();
    }

    public function model_relations()
    {
        return ['owner', 'category'];
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function payments()
    {
        return $this->belongsToMany(PaymentMethod::class, 'branch_payments');
    }

    public function days()
    {
        return $this->hasMany(Day::class, 'branch_id');
    }

    public function related_branches()
    {
        return $this->hasMany(Branch::class, 'owner_id', 'owner_id');
        // return $this->hasMany(Branch::class, 'owner_id', 'owner_id')->whereNotIn('id', [$this->id]);
    }

    public function is_favorate()
    {
        return $this->hasOne(Favorite::class, 'branch_id')->where('user_id', auth()->guard('api')->user()->id);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
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
    public function getExpireAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d M Y, h:i A') : null;
    }
    /**
     * Get the sub category associated with the Branch
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subCategory()
    {
        return $this->belongsTo(\App\Models\SubCategory::class, 'sub_category_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
