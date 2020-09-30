<?php

use App\Supplier;
use Illuminate\Database\Seeder;


class SupplierSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Supplier::truncate();
		for($x = 0; $x<3;$x++){
			$faker = Faker\Factory::create();
			$supplier = [
				'name'=>$faker->company,
				'telephone'=>$faker->phoneNumber,
				'block'=>$faker->postcode,
				'street'=>$faker->streetName,
				'city'=>$faker->city,
			];
			Supplier::create($supplier);
		}
    }
}

