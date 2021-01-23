<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItems extends Model
{
	protected $fillable = ['purchase_order_id','product_id','requested_units','expiry_date','received_units','unit_price','total_price' ];

	public function product(){
		return $this->belongsTo('App\Product');
	}

	public function getNameAttribute(){
		return $this->product->name;
	}

	public function setExpiryDateAttribute($value)
	{
		$this->attributes['expiry_date'] = Carbon::parse($value)->format('Y-m-d');
	}
}
