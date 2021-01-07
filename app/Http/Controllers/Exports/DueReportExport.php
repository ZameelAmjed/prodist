<?php

namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class DueReportExport implements FromCollection, WithHeadings {
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
			'Business Name',
			'Order Id',
			'Supplier Ref.',
			'Payment Type',
			'Payment Status',
			'Payment Amount',
			'Payment Date',
			'Total Amount',
			'Return Amount',
			'Delivery Date',
			'Created At',
			'Credit Period',
			'Store Id',
			'Duration',
			'Delay',
		];
	}
}