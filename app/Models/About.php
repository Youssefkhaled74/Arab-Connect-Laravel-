<?php

namespace App\Models;

use Illuminate\Support\Facades\File;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;	

class About extends Model
{
	use HasRoles,LogsActivity;
	protected $table = 'abouts';
	protected $fillable = ['content', 'type', 'img'];
    public $timestamps = true;
	public $appends = ['type_name'];

	
    protected static $logAttributes = ['*']; 
    protected static $logName = 'Abouts';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() 
            ->logOnlyDirty() 
            ->useLogName('Abouts')
			->setDescriptionForEvent(fn(string $eventName) => match ($eventName) {
				'created' => 'تم إنشاء هذا النموذج بنجاح',
				'updated' => 'تم تحديث هذا النموذج بنجاح',
				'deleted' => 'تم حذف هذا النموذج بنجاح',
				default => "تم تنفيذ الحدث: {$eventName}",
			})            
			->dontSubmitEmptyLogs();
    }

	public function getTypeNameAttribute()
	{
		if ((int)$this->type == 1) {
			return "من نحن";
		} else if ((int)$this->type == 2) {
			return "لماذا تختارنا";
		} else if ((int)$this->type == 3) {
			return "ماذا نفدم";
		} else {
			return "";
		}
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
		return ['content' => '', 'type' => '', 'img' => ''];
	}

	public function scopeModelSearch($model, $query)
	{
		return $model->latest()->where('id', 'LIKE', '%'. $query .'%')
					 ->orWhere('content', 'LIKE', '%'. $query .'%')
					 ->unArchive()->get();
	}

	public function scopeModelSearchInArchives($model, $query)
	{
		return $model->latest()->where('id', 'LIKE', '%'. $query .'%')
					 ->orWhere('content', 'LIKE', '%'. $query .'%')
					 ->archive()->get();
	}

	public function model_relations()
	{
		return [];
	}

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($about) {
            if ($about->img) {
                $imagePath = public_path('admin/assets/images/abouts/' . $about->img);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }
        });
    }
}