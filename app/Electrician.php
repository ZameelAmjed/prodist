<?php

namespace App;
use App\Http\Controllers\Traits\Helper;
use DateInterval;
use DateTime;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;


class Electrician extends Authenticatable implements  JWTSubject
{
	use Notifiable;
    //php artisan electrician:import 100ele1 TO IMPORT EXCEL FILE
	protected $fillable = ['name', 'nic', 'telephone', 'block', 'street', 'city',
		'province', 'date_of_birth', 'celebration',
		'bank_account_no','bank_name','bank_city','bank_code',
		'region', 'area','dealer_id', 'language','points','member_code'];

	public function approvedBy()
	{
		return $this->belongsTo('App\User','approved_by','id');
	}

	public function payments()
	{
		return $this->hasMany('App\RewardsPayment','electrician_id','id')->orderBy('created_at','desc');
	}

	public function getRewards()
	{
	 return Setuprepo::where('electrician','=',$this->id)->get();
	}

	public function dealer()
	{
		return $this->belongsTo('App\Dealers','dealer_id','id');
	}

	public function paymentRequest()
	{
		return $this->hasMany('App\PaymentRequest','electrician_id','id')->orderBy('created_at','desc');
	}

	/**
	 * Get the identifier that will be stored in the subject claim of the JWT.
	 *
	 * @return mixed
	 */
	public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Return a key value array, containing any custom claims to be added to the JWT.
	 *
	 * @return array
	 */
	public function getJWTCustomClaims()
	{
		return [];
	}

	public function getAuthPassword()
	{
		return $this->telephone;
	}

	public function setPasswordAttribute($value)
	{
		$this->attributes['telephone'] = bcrypt($value);
	}

	public function setDateOfBirthAttribute($value){
		$nic = $this->attributes['nic'];
		if(empty($value) && $nic){
			$this->attributes['date_of_birth'] = Helper::getdateofbirthfromnic($nic);
		}else{
			$this->attributes['date_of_birth'] = $value;
		}
	}

	/**
	 * @param $value
	 * Set Membership Id
	 */
	public function setMemberCode(){
		if(empty($this->member_code)){
			$code = strtoupper(substr($this->province,0,3));
			$this->member_code = $code.str_pad($this->id,6,0,STR_PAD_LEFT);
			$this->save();
		}
	}

	public function getDateOfBirthAttribute($value){
		return date('Y-m-d', strtotime($value));
	}

}
