<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;	
use Illuminate\Notifications\Notifiable;


class Admin extends Authenticatable
{
	use HasRoles,LogsActivity,Notifiable;
	protected $table = 'admins';
	protected $fillable = ['name', 'email', 'phone', 'password', 'img', 'role_id','is_activate'];
    public $timestamps = true;

	protected static $logAttributes = ['*']; 
    protected static $logName = 'Admins';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() 
            ->logOnlyDirty() 
            ->useLogName('Admins')
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
		return ['name' => '', 'email' => '', 'phone' => '', 'password' => '', 'img' => '', 'role_id' => ''];
	}

	public function scopeModelSearch($model, $query)
	{
		return $model->latest()->where('id', 'LIKE', '%'. $query .'%')
					 ->orWhere('email', 'LIKE', '%'. $query .'%')
					 ->orWhere('name', 'LIKE', '%'. $query .'%')
					 ->orWhere('phone', 'LIKE', '%'. $query .'%')
					 ->unArchive()->get();
	}

	public function scopeModelSearchInArchives($model, $query)
	{
		return $model->latest()->where('id', 'LIKE', '%'. $query .'%')
					 ->orWhere('email', 'LIKE', '%'. $query .'%')
					 ->orWhere('name', 'LIKE', '%'. $query .'%')
					 ->orWhere('phone', 'LIKE', '%'. $query .'%')
					 ->archive()->get();
	}

	public function model_relations()
	{
		return [];
	}
	public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }
}