<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
class Setuprepo extends Eloquent
{
	protected $connection = 'mongodb';
	protected $collection = 'setuprepo';

	protected $fillable = [
		'barcode', 'electrician', 'model', 'user', 'points',
	];

	public function getElectricianInfoAttribute()
	{
		return Electrician::find($this->attributes['electrician']);
	}


}
