<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesDemographsSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $region = [
        	'central',
	        'colombo-01',
	        'colombo-02',
	        'southern',
	        'east',
	        'north western',
	        'north'
        ];
	    $m = DB::connection('mongodb')->collection('region');
        foreach ($region as $val){
	        $m->insert(['name'=>$val]);
        }


	    $area = [
		    "colombo",
		    "dehiwala-mount lavinia",
		    "moratuwa",
		    "jaffna",
		    "negombo",
		    "pita kotte",
		    "sri jayewardenepura kotte",
		    "kandy",
		    "trincomalee",
		    "kalmunai",
		    "galle",
		    "point pedro",
		    "batticaloa",
		    "katunayaka",
		    "valvedditturai",
		    "matara",
		    "battaramulla south",
		    "dambulla",
		    "maharagama",
		    "kotikawatta",
		    "anuradhapura",
		    "vavuniya",
		    "kolonnawa",
		    "hendala",
		    "ratnapura",
		    "badulla",
		    "puttalam",
		    "devinuwara",
		    "welisara",
		    "kalutara",
		    "bentota",
		    "matale",
		    "homagama",
		    "beruwala",
		    "panadura",
		    "mulleriyawa",
		    "kandana",
		    "ja ela",
		    "wattala",
		    "peliyagoda",
		    "kelaniya",
		    "kurunegala",
		    "nuwara eliya",
		    "gampola",
		    "chilaw",
		    "eravur town",
		    "hanwella ihala",
		    "weligama",
		    "vakarai",
		    "kataragama",
		    "ambalangoda",
		    "ampara",
		    "kegalle",
		    "hatton",
		    "polonnaruwa",
		    "kilinochchi",
		    "tangalle",
		    "monaragala",
		    "wellawaya",
		    "gampaha",
		    "horana south",
		    "wattegama",
		    "minuwangoda",
		    "horawala junction",
		    "kuliyapitiya",
		    "mihintale",
		    "ella town",
		    "haputale",
		    "mirissa city",
		    "talawakele",
		    "talpe",
		    "koggala",
		    "unawatuna",
		    "hikkaduwa",
		    "kadugannawa",
		    "sigiriya",
		    "amblangoda",
		    "kurunagala",
		    "embilipitiya",
		    "kegelle",
		    "horana"
	    ];
	    $n = DB::connection('mongodb')->collection('area');
	    foreach ($area as $val){
		    $n->insert(['name'=>$val]);
	    }
    }
}
