<?php

namespace App;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    protected $table  = 'payment_requests';

    protected $fillable = ['electrician_id','amount','status'];

    public function getCreatedAtAttribute($date) {
	    return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');
    }

    public function electrician(){
    	return $this->belongsTo('App\Electrician','electrician_id','id');
    }
}
