<?php
/**
 * Project: chint
 * File Name: BarcodeExport.php
 * Author: Zameel Amjed
 * Date: 2/18/2020
 * Time: 4:59 PM
 */
namespace App\Http\Controllers\Exports;

use App\Http\Controllers\Traits\ProductTrait;
use Maatwebsite\Excel\Concerns\FromArray;

class BarcodeExport implements FromArray{
	use ProductTrait;
	protected $values;

	function __construct(...$args){
		$this->values = $args;
	}

	public function array():array
	{
		$start = $this->values[1];
		$end = $this->values[2];
		$data = [];

		for(;$start<$end;$start++){
			$code = $this->codePrinter($this->values[0]->textcode,$start);
			$data[][] = $code;
		}

		return [
			$data
		];
	}
}