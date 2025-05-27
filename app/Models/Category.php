<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;	

class Category extends Model
{
	use HasRoles,LogsActivity;
    protected $table = 'categories';
	protected $fillable = ['name', 'img'];
    public $timestamps = true;

	protected static $logAttributes = ['*']; 
    protected static $logName = 'Categories';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() 
            ->logOnlyDirty() 
            ->useLogName('Categories')
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
		return ['name'  => '', 'img'  => ''];
	}

	public function scopeModelSearch($model, $query)
	{
		return $model->latest()->where('id', 'LIKE', '%'. $query .'%')
					 ->orWhere('name', 'LIKE', '%'. $query .'%')
					 ->unArchive()->limit(PAGINATION_COUNT_FRONT)->get();
	}

	public function scopeModelSearchInArchives($model, $query)
	{
		return $model->latest()->where('id', 'LIKE', '%'. $query .'%')
					 ->orWhere('name', 'LIKE', '%'. $query .'%')
					 ->archive()->limit(PAGINATION_COUNT_FRONT)->get();
	}

	public function model_relations()
	{
		return [];
	}

	public function branches()
	{
		return $this->hasMany(Branch::class, 'category_id')->unArchive()->published()->latest();
	}
}
