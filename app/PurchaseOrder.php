<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
	const pending = 'requested';

	const complete = 'received';

	const cancel = 'cancelled';

	protected $fillable = ['supplier_id','user_id','batch_no','total_amount','status','supplier_ref_code'];

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

	public function purchaseOrderItems(){
		return $this->hasMany('App\PurchaseOrderItems')->with('product');
	}

	public function getUidAttribute()
	{
		return config('app.po_prefix').str_pad($this->attributes['id'],5,0,STR_PAD_LEFT);
	}

}
