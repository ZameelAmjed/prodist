<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierOrder extends Model
{
	const pending = 'requested';

	const complete = 'received';

	const cancel = 'cancelled';

	protected $fillable = ['supplier_id','user_id','batch_no','total_amount','status'];

	protected $appends = ['uid'];

	public function getCreatedAtAttribute($value){
		return date('Y-m-d', strtotime($value));
	}

	public function getUpdatedAtAttribute($value){
		return date('Y-m-d', strtotime($value));
	}

	public function supplier(){
		return $this->belongsTo('App\Supplier');
	}

	public function supplierOrderItems(){
		return $this->hasMany('App\SupplierOrderItems')->with('product');
	}

	public function getUidAttribute()
	{
		return config('app.po_prefix').str_pad($this->attributes['id'],5,0,STR_PAD_LEFT);
	}

}
