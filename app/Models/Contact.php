<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';
	protected $fillable = [
		'name',
		'email',
		'mobile',
		'message',
	];
    public $timestamps = true;

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
			'name' => '',
			'email' => '',
			'mobile' => '',
			'message' => '',
		];
	}

	public function scopeModelSearch($model, $query)
	{
		return $model->latest()->where('id', 'LIKE', '%'. $query .'%')
					 ->orWhere('name', 'LIKE', '%'. $query .'%')
					 ->orWhere('email', 'LIKE', '%'. $query .'%')
					 ->orWhere('mobile', 'LIKE', '%'. $query .'%')
					 ->orWhere('message', 'LIKE', '%'. $query .'%')
					 ->unArchive()->get();
	}

	public function scopeModelSearchInArchives($model, $query)
	{
		return $model->latest()->where('id', 'LIKE', '%'. $query .'%')
					 ->orWhere('name', 'LIKE', '%'. $query .'%')
					 ->orWhere('email', 'LIKE', '%'. $query .'%')
					 ->orWhere('mobile', 'LIKE', '%'. $query .'%')
					 ->orWhere('message', 'LIKE', '%'. $query .'%')
					 ->archive()->get();
	}

	public function model_relations()
	{
		return [];
	}
}