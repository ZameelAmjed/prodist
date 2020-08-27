<?php

namespace App\Http\Controllers\Traits;



use App\Product;
use App\Setuprepo;

trait ProductTrait
{

	public function codePrinter($productcode, $productid)
	{
		return $productcode.'-'.str_pad($productid,10,0,STR_PAD_LEFT).'-'.$this->getSecurityCode($productcode, $productid);
	}

	public function getSecurityCode($productcode, $productid){
		$salt = env('BARCODE_SALT','CRYPT');
		$factor = (int)($productid % 47);
		$code = $this->shuffleString($productcode.$salt.$productid, $factor);
		return (substr($code, -3));
	}

	private function shuffleString($inputString, $seed = 2000){

		$strLength = strlen($inputString);
		$newString = "";
		$factor = 3 + (int)($seed / $strLength);

		while($strLength > 0){

			$seed += $factor;

			if($seed >= $strLength){
				$seed = $seed % $strLength;
			}

			$newString .= $inputString[$seed];

			$copyStr = "";

			$strLength --;

			if($seed != 0){
				$copyStr = substr($inputString,0,$seed);
			}

			if($seed != $strLength){
				$copyStr .= substr($inputString,$seed+1);
			}

			$inputString = $copyStr;

		}

		return $newString.$inputString;

	}

	public function validateBarcode($barcode){
		$product_info = explode('-',$barcode);
		if( !isset($product_info[1]) )
			return ['status'=>false, 'message'=>'Invalid Barcode entry'];
			//check whether points already given
		if(Setuprepo::where('barcode','=',$barcode)->exists()){
			return ['status'=>false, 'message'=>"Barcode {$barcode} already entered for points",'used'=>true];
		}
		if($this->codePrinter($product_info[0], ltrim($product_info[1], '0'))===$barcode){
			//send product info
			return ['status'=>true,'data'=>Product::where('textcode','=',$product_info[0])->first()];
		}else{
			return ['status'=>false, 'message'=>'Invalid Barcode entry'];
		}
	}
}