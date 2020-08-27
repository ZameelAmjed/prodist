<?php

use Illuminate\Database\Seeder;
use App\Product;
use App\Setuprepo;

class ProductsSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    Setuprepo::truncate();
	    Product::truncate();
       /* $products = Product::create([
            'product_name' => 'Switch',
            'model' => 'MK-101',
	        'description' => 'Gold Line',
	        'textcode'=>'MK',
	        'points' => 100
        ]);*/

    }
}

