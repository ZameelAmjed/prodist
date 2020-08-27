<?php
/**
 * Project: chint
 * File Name: AuthController.php
 * Author: Zameel Amjed
 * Date: 3/16/2020
 * Time: 10:09 AM
 */

namespace App\Http\Controllers\API;






use App\Dealers;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class HelperApiController extends ResponseController
{

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * getarea?area=rat or region
	 */
	public function getAreas(Request $request)
	{
		if($request->get('region')){
			$q = $request->get('region');
			 $data = DB::connection( 'mongodb' )
			           ->collection( 'area' )
			           ->where( 'region','like',"%$q%")
			 ->get();
		}

		if($request->get('area')){
			$q = $request->get('area');
			$data = DB::connection( 'mongodb' )
			          ->collection( 'area' )
			          ->where( '_id','like',"%$q%")
			          ->get();
		}

		return response()->json($data);
	}


	public function getProducts(Request $request)
	{
		if($request->get('code')){
			$q = $request->get('code');
			 $data = Product::where('product_name','like',"%$q%")
			                ->orWhere('model','like',"%$q%")
			                ->get();
			 return response()->json($data);
		}
	}


	public function getBank(Request $request)
	{
		if($request->get('name')){
			$q = $request->get('name');
			 $data = DB::connection( 'mongodb' )
			           ->collection( 'banks' )
			           ->where( 'bankname','like',"%$q%")
			 ->get();
			return response()->json($data);
		}

		if($request->get('code')){
			$q = $request->get('name');
			$data = DB::connection( 'mongodb' )
			          ->collection( 'banks' )
			          ->where( 'bankname','=',"%$q%")
			          ->get();
			return response()->json($data);
		}


	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 *
	 * Get Dealers Name on Match
	 */
	public function getDealer(Request $request)
	{
		if($request->get('name')){
			$q = $request->get('name');
			$data = Dealers::select('id','name','business_name','region','area')->where('name','like',"%$q%")
			               ->orWhere('business_name','like',"%$q%")
			               ->take(10)->get();
			return response()->json($data);
		}
	}

}