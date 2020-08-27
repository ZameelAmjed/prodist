<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dealers extends Model
{
	protected $fillable = ['business_name','name', 'telephone','mobile', 'block', 'street',
		'city', 'region', 'area','dealer_type'];

	public function electrician()
	{
		return $this->hasMany('App/Electrician','dealer_id','id');
	}
}
