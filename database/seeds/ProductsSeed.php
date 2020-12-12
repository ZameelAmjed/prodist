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
			'name'              => 'Flora Butter',
			'supplier_id'       => 2
		],
		[
			'name'              => 'Anchor Butter',
			'supplier_id'       => 2
		],
		[
			'name'              => 'Margarine 200g',
			'supplier_id'       => 1
		],
		[
			'name'              => 'Yeast 300g',
			'supplier_id'       => 1
		],
		[
			'name'              => 'Cocoa 50g Pack',
			'supplier_id'       => 1
		],
		[
			'name'              => 'Bee Seasoning Mix',
			'supplier_id'       => 1
		],
		[
			'name'              => 'Cooking Chocolate 200',
			'supplier_id'       => 2
		],
		[
			'name'              => 'Milky Roast Cream',
			'supplier_id'       => 1
		],
		[
			'name'              => 'Fortuner Sesame Seeds',
			'supplier_id'       => 2
		],
	];

	public function run() {
		Product::truncate();
		for ( $x = 0; $x < count($this->items); $x ++ ) {

			$product = [
				'name'              => $this->items[ $x ]['name'],
				'supplier_id'       => $this->items[ $x ]['supplier_id'] ?? 0,
				];
			Product::create( $product );
		}
	}

}

