<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
class Duplicates extends Eloquent
{
	protected $connection = 'mongodb';
	protected $collection = 'redundent_requests';

	protected $fillable = [
		'barcode', 'electrician', 'ip', 'user',
	];
}
