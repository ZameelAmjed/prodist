<?php

use App\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class LocationSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Location::truncate();
       $content = json_decode(File::get(public_path('assets/sl_cities.json')));
	    foreach ($content as $key=>$val){
		    foreach ($val->options as $val2){
			   Location::create([
				   'city' => $val2,
				   'region' => $key,
				   'province' => $key,
				   'code' => strtoupper(substr($val2,0,3))
			   ]);
		    }
	    }
    }
}
