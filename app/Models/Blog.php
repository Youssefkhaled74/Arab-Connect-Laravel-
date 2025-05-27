<?php

namespace App\Models;

use Illuminate\Support\Facades\File;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;	

class Blog extends Model
{
	use HasRoles,LogsActivity;
    protected $table = 'blogs';
	protected $fillable = ['title', 'slug', 'description', 'imgs', 'category_id', 'meta_title', 'meta_description', 'meta_tags', 'meta_keywords'];
    public $timestamps = true;

	protected static $logAttributes = ['*']; 
    protected static $logName = 'Blogs';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() 
            ->logOnlyDirty() 
            ->useLogName('Blogs')
			->setDescriptionForEvent(fn(string $eventName) => match ($eventName) {
				'created' => 'تم إنشاء هذا النموذج بنجاح',
				'updated' => 'تم تحديث هذا النموذج بنجاح',
				'deleted' => 'تم حذف هذا النموذج بنجاح',
				default => "تم تنفيذ الحدث: {$eventName}",
			})            
			->dontSubmitEmptyLogs();
    }

	public function scopeActive($query){
		return $query->where('is_activate', 1);
	}

	public function scopeUnActive($query){
		return $query->where('is_activate', 0);
	}

	public function scopeArchive($query){
		return $query->whereNotNull('deleted_at');
	}

	public function scopeUnArchive($query){
		return $query->whereNull('deleted_at');
	}

	public function fildes(){
		return [
			'title' => '', 'slug' => '', 'description' => '', 'imgs' => '', 'category_id' => '',
			'meta_title' => '', 'meta_description' => '', 'meta_tags' => '', 'meta_keywords' => ''
		];
	}

	public function scopeModelSearch($model, $query)
	{
		return $model->latest()->where('id', 'LIKE', '%'. $query .'%')
					 ->orWhere('title', 'LIKE', '%'. $query .'%')
					 ->orWhere('slug', 'LIKE', '%'. $query .'%')
					 ->orWhere('description', 'LIKE', '%'. $query .'%')
					 ->orWhere('meta_title', 'LIKE', '%'. $query .'%')
					 ->orWhere('meta_description', 'LIKE', '%'. $query .'%')
					 ->orWhere('meta_tags', 'LIKE', '%'. $query .'%')
					 ->orWhere('meta_keywords', 'LIKE', '%'. $query .'%')
					 ->unArchive()->get();
	}

	public function scopeModelSearchInArchives($model, $query)
	{
		return $model->latest()->where('id', 'LIKE', '%'. $query .'%')
					 ->orWhere('title', 'LIKE', '%'. $query .'%')
					 ->orWhere('slug', 'LIKE', '%'. $query .'%')
					 ->orWhere('description', 'LIKE', '%'. $query .'%')
					 ->orWhere('meta_title', 'LIKE', '%'. $query .'%')
					 ->orWhere('meta_description', 'LIKE', '%'. $query .'%')
					 ->orWhere('meta_tags', 'LIKE', '%'. $query .'%')
					 ->orWhere('meta_keywords', 'LIKE', '%'. $query .'%')
					 ->archive()->get();
	}

	public function model_relations()
	{
		return [];
	}

	public function category()
	{
		return $this->belongsTo(Category::class, 'category_id');
	}

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($about) {
            if ($about->img) {
                $imagePath = public_path('admin/assets/images/blogs/' . $about->img);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }
        });
    }
}
