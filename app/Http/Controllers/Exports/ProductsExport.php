<?php
/**
 * Project: chint
 * File Name: BarcodeExport.php
 * Author: Zameel Amjed
 * Date: 2/18/2020
 * Time: 4:59 PM
 */
namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class ProductsExport implements FromCollection, WithHeadings {
	protected $values;

	function __construct(...$args){
		$this->values = $args;
	}

	public function collection()
	{
		return $this->values[0]->get();
	}

	public function headings(): array
	{
		return [
			'ID',
			'Product Name',
			'Model',
			'Description',
			'Text Code',
			'Points',
			'Units Issued',
			'Units Active',
			'Last Barcode'
		];
	}
}