<?php
namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DealersImp implements ToModel, WithHeadingRow
{
use Importable;

public function model(array $row)
{
foreach ($row as $key=>$val){
	$row[$key] = strtolower(trim($val));

	/*ADDRESS SPLITER*/
	if($key=='address'){
		$arr = explode(',',$row[$key]);

		if(count($arr)==3){
			$row['block'] = $arr[0];
			$row['street'] =$arr[1];
			$row['city'] =$arr[2];
			$row['area'] =$arr[2];
		}else if(count($arr)==2){
			$row['block'] = '';
			$row['street'] =$arr[0];
			$row['city'] =$arr[1];
			$row['area'] =$arr[1];
		}else{
			$row['block'] = '';
			$row['street'] =$row[$key];
			$row['city'] ='';
			$row['area'] ='';
		}

	}
}

//Tackle Empty Records
if(empty($row['business_name'])&&empty($row['business_name']))
	return;

return new Dealers([
	'business_name'=> $row['business_name'],
	'name'=> ucwords(preg_replace('/\./m','. ',$row['name'])),
	'telephone'=> $row['land'],
	'mobile'=> $row['mobile'],
	'block'=> $row['block'],
	'street'=> trim($row['street']),
	'city'=> trim($row['city']),
	'region'=> trim($row['region']),
	'area'=> trim(($row['area'])?$row['area']:$row['region'])
]);
}
}
/*MEMBER_ID
	NAME
	NIC
	TELE
	REIGON
	AREA
	DEALER
	CELEBRATION
	LANGUAGE
	POINTS
	LEVEL[X]
	ROLE
	BANK_NO
	BANK_NAME
	BANK_CITY
	ADDRESS_1
	ADDRESS_CITY*/