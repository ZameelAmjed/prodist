<?php

namespace App\Http\Controllers\Traits;


use Illuminate\Support\Facades\DB;

trait Helper {
	public static function getareas() {
		return DB::connection( 'mongodb' )->collection( 'area' )->get();
	}

	public static function getregions() {
		return DB::connection( 'mongodb' )->collection( 'region' )->get();
	}

	public static function getdealers() {
		return Dealers::all( 'id', 'name' );
	}

	public static function sanitizeLkTelephone($value) {
		if(strlen($value) == 10 ){
			return '0094'.substr($value,1,9);
		}
		if(strlen($value) == 9 ){
			return '0094'.$value;
		}
		return "$value";
	}

	public static function getdateofbirthfromnic( $nic ) {
		$dayText = 0;
		$year    = "";
		$month   = "";
		$day     = "";
		$gender  = "";

		// $year
		if ( strlen( $nic ) <= 10 ) {
			$year    = "19" . substr( $nic, 0, 2 );
			$dayText = substr( $nic, 2, 3 );
		} else {
			$year    = substr( $nic, 0, 4 );
			$dayText = substr( $nic, 4, 3 );
		}

		// $gender
		if ( $dayText > 500 ) {
			$gender  = "Female";
			$dayText = $dayText - 500;
		} else {
			$gender = "Male";
		}

		// $day Digit Validation
		if ( $dayText < 1 && $dayText > 366 ) {
			return;
		} else {

			//$month
			if ( $dayText > 335 ) {
				$day   = $dayText - 335;
				$month = "12";
			} else if ( $dayText > 305 ) {
				$day   = $dayText - 305;
				$month = "11";
			} else if ( $dayText > 274 ) {
				$day   = $dayText - 274;
				$month = "10";
			} else if ( $dayText > 244 ) {
				$day   = $dayText - 244;
				$month = "09";
			} else if ( $dayText > 213 ) {
				$day   = $dayText - 213;
				$month = "08";
			} else if ( $dayText > 182 ) {
				$day   = $dayText - 182;
				$month = "07";
			} else if ( $dayText > 152 ) {
				$day   = $dayText - 152;
				$month = "06";
			} else if ( $dayText > 121 ) {
				$day   = $dayText - 121;
				$month = "05";
			} else if ( $dayText > 91 ) {
				$day   = $dayText - 91;
				$month = "04";
			} else if ( $dayText > 60 ) {
				$day   = $dayText - 60;
				$month = "03";
			} else if ( $dayText < 32 ) {
				$month = "01";
				$day   = $dayText;
			} else if ( $dayText > 31 ) {
				$day   = $dayText - 31;
				$month = "02";
			}
		}

		return "$year-$month-$day";
	}
}