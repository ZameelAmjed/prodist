<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RewardsPayment extends Model
{
    //
	const REDUCTION = 'REDUCTION';
	protected $fillable = ['electrician_id','points','transfer_type','comment','confirmation_message','payed_on'];

	public function electrician()
	{
		return $this->belongsTo('App\Electrician', 'electrician_id', 'id');
	}
}
