<?php
/**
 * Project: chint
 * File Name: AuthController.php
 * Author: Zameel Amjed
 * Date: 3/16/2020
 * Time: 10:09 AM
 */

namespace App\Http\Controllers\API;

use App\Location;
use App\Product;
use App\ProductDiscount;
use App\Store;
use Dotenv\Regex\Regex;
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
			 $data = Location::select('region')->where('region','like',"%$q%")->groupby('region')->get();
		}

		if($request->get('city')){
			$q = $request->get('city');
			$data = Location::where('city','like',"%$q%")->get();
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
		if($request->get('supplier')){
			$q = $request->get('q');
			$supplier = $request->get('supplier');
			$data = Product::where('name','like',"%$q%")
				->where('supplier_id',$supplier)
			               ->take(10)->get();
			return response()->json($data);
		}
		if($request->get('id')){
			$q = $request->get('id');
			$arr = str_getcsv($q);
			$data = Product::whereIn('id',$arr)
			               ->get();
			return response()->json($data);
		}

		if($request->get('q')){
			$q = $request->get('q');
			$data = Product::with('productDiscounts')
			               ->where('name','like',"%{$q}%")
			               ->get();

			return response()->json($data);
		}
	}

	public function getBrands(Request $request)
	{
		if($request->get('name')){
			$query = [];
			$json = File::get('assets\brands.json');
			$json = json_decode($json);
			if(!strlen($request->get('name'))>3)
				return;
			foreach ($json as $v) {
				$regex = new Regex();
				if(preg_match('/'.$request->get('name').'/i',$v)){
					$query [] = ['brand'=>$v];
				}
			}
			return response()->json($query);
		}
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getStores(Request $request){
		if($request->get('q')){
			$q = $request->get('q');
			$data = Store::where('business_name','like',"%{$q}%")
			             ->orWhere('owner_name','like',"%{$q}%")
			             ->get();
			return response()->json($data);
		}
	}

	public function getStoresWithDue(Request $request){
		$q = $request->get('q');
		
		//:todo add this on production to reduce DB requests
		/*if(strlen($q)<3)
			return;*/

		$data = DB::table('stores')
            ->join('orders', function ($join) {
            $join->on('stores.id', '=', 'orders.store_id')
                 ->where('orders.status', '=', 'processing');
        })
            ->leftJoin('payments', function ($join) {
            $join->on('orders.id', '=', 'payments.order_id')
                 ->where('payments.status', '=', 'accept');
        })
            ->where('business_name','like',"%{$q}%")
			->orWhere('owner_name','like',"%{$q}%")
->select(DB::raw('stores.id, stores.business_name, stores.owner_name, stores.block, stores.street, stores.city, SUM(orders.total_amount) as total_amount, SUM(payments.payment_amount) as total_payment'))
->groupby('stores.id', 'stores.business_name','stores.owner_name','stores.block', 'stores.street', 'stores.city')
			->get();
			return response()->json($data);
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