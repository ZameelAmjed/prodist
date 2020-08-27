<?php
namespace App\Http\Controllers\Exports;
use App\Product;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class ProductsImp implements ToCollection, WithHeadingRow
{
use Importable;

	public function collection(Collection $rows)
	{
		foreach ($rows as $row)
		{
			$product = Product::where('model',$row['product_code'])->first();
			if(!$product){
				Product::create([
					'product_name' => $row['product_name'],
					'model' => $row['product_code'],
					'description' => $row['description'],
					'textcode' => $row['textcode']??'',
					'points' => $row['points'],
					'series' => str_replace(' ','', $row['series']),
					'category' => strtolower($row['category'])
				]);
			}else{
				$product->product_name = $row['product_name'];
				$product->description = $row['description'];
				$product->points = $row['points'];
				$product->series = $row['series'];
				$product->category = $row['category'];
				$product->save();
			}

		}
	}
}
