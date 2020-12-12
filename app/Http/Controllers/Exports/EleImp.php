<?php
namespace App\Http\Controllers\Exports;
use App\Store;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EleImp implements ToModel, WithHeadingRow
{
use Importable;

public function model(array $row)
{
foreach ($row as $key=>$val){
	$row[$key] = strtolower(trim($val));
}

return new Store([
	'name'=>ucwords(preg_replace('/\./m','. ',$row['name'])),
	'nic'=>str_replace(' ','',trim($row['nic'])),
	'telephone'=>$row['telephone'],
	'block'=>$row['address_1'],
	//'street'=>$row[],
	'city'=>$row['address_city'],
	'province'=>$row['reigon'],
	'member_code'=>str_replace(' ','',strtoupper($row['member_id'])),
	'bank_account_no'=>$row['bank_no'],
	'bank_name'=>$row['bank_name'],
	'bank_city'=>$row['bank_city'],
	'region'=>$row['reigon'],
	'area'=>$row['area'],
	'dealer_id'=>$row['dealer_id'],
	'language'=>$row['language'],
	'celebration'=>$row['celebration'],
	'points'=>floatval($row['points'])
	//'bank_code'=>$row[]
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