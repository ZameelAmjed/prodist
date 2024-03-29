<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
	public $timestamps = false;

    protected $table = 'location';

    protected $fillable = ['city','region','province','code'];

}
