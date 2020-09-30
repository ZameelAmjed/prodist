<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierOrderItems extends Model
{
	protected $fillable = ['supplier_order_id','product_id','requested_units','expiry_date','received_units','discount','unit_price','total_price' ];

	public function product(){
		return $this->belongsTo('App\Product');
	}

	public function getNameAttribute(){
		return $this->product->name;
	}
}
