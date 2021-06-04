<?php

use Illuminate\Database\Seeder;
use App\Product;


class ProductsSeed extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public $items = [
		[
			'name'              => 'Beehive 1kg',
			'supplier_id'      => 1,
			'retail_price'		=> 500
		],
		[
			'name'              => 'Beehive 20kg',
			'supplier_id'      => 1,
			'retail_price'		=> 5000
		],
		[
			'name'              => 'Hyco 20kg',
			'supplier_id'       => 1,
			'retail_price'		=> 9000
		],
		[
			'name'              => 'Superfine 20kg',
			'supplier_id'       => 1,
			'retail_price'		=> 40000
		],
		[
			'name'              => 'Superfine 5kg',
			'supplier_id'       => 1,
			'retail_price'		=> 400
		],
		[
			'name'              => 'Creaming Fat 15kg',
			'supplier_id'       => 1,
			'retail_price'		=> 5000
		],
		[
			'name'              => 'Blue Team 15kg',
			'supplier_id'      => 1,
			'retail_price'		=> 8000
		],
		[
			'name'              => 'Masterpuff 15kg',
			'supplier_id'       => 1,
			'retail_price'		=> 9000
		],
		[
			'name'              => 'Masterbunn 15kg',
			'supplier_id'      => 1,
			'retail_price'		=> 8500
		],
		[
			'name'              => 'Frytol 20kg',
			'supplier_id'      => 1,
			'retail_price'		=> 7500
		],
		[
			'name'              => 'Frytol 5kg',
			'supplier_id'      => 1,
			'retail_price'		=> 450
		],
		[
			'name'              => 'Maja Pan Lubricant 4 in 1',
			'supplier_id'      => 1,
			'retail_price'		=> 600
		],
		[
			'name'              => 'Yeast 20kg',
			'supplier_id'      => 1,
			'retail_price'		=> 6000
		],
		[
			'name'              => 'Meadowlea 5kg',
			'supplier_id'      => 1,
			'retail_price'		=> 600
		],
		[
			'name'              => 'Masterbunn 1kg',
			'supplier_id'      => 1,
			'retail_price'		=> 300
		],
	];
	public function run() {
		Product::truncate();
		for ( $x = 0; $x < count($this->items); $x ++ ) {

			$product = [
				'name'              => $this->items[ $x ]['name'],
				'supplier_id'       => $this->items[ $x ]['supplier_id'] ?? 0,
				'retail_price' => $this->items[ $x ]['retail_price'],
				];
			Product::create( $product );
		}
	}

}

