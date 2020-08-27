<?php

namespace App;


use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
class Product extends Eloquent
{
	protected $connection = 'mongodb';
	protected $collection = 'products';

	protected $fillable = [
		'product_name', 'model', 'description', 'textcode', 'points','series','category'
	];

	protected $guarded = [
		'units_issued', 'units_active', 'last_barcode'
	];

	public function setTextcodeAttribute($code){
		if($code==null){
			$code = substr(str_replace(' ','',$this->attributes['product_name']),0,2);
			$this->attributes['textcode'] = strtoupper("U$code");
		}
	}

	//e.g Switch, SK-001, Gold Frame Switch, 'BK', 1000 units, last_barcode,
}
